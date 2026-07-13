<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $userIds = $this->tenantUserIds($request->user());

        $logs = AuditLog::query()
            ->with('user')
            ->whereIn('user_id', $userIds)
            ->latest()
            ->take(100)
            ->get()
            ->map(fn (AuditLog $log) => $this->presentLog($log));

        return Inertia::render('AuditLogs/Index', [
            'logs' => $logs,
            'summary' => [
                'total' => $logs->count(),
                'create_count' => $logs->where('method', 'POST')->count(),
                'update_count' => $logs->whereIn('method', ['PUT', 'PATCH'])->count(),
                'delete_count' => $logs->where('method', 'DELETE')->count(),
            ],
        ]);
    }

    private function tenantUserIds(User $user): array
    {
        $ownerId = $user->tenantOwnerId();

        return User::query()
            ->where('id', $ownerId)
            ->orWhere('parent_user_id', $ownerId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function presentLog(AuditLog $log): array
    {
        return [
            'id' => $log->id,
            'user' => $log->user?->name ?? 'Sistem',
            'email' => $log->user?->email,
            'action' => $log->action,
            'action_label' => $this->actionLabel($log),
            'module_label' => $this->moduleLabel($log->route_name),
            'method' => $log->method,
            'method_label' => $this->methodLabel($log->method),
            'route_name' => $log->route_name,
            'url' => $log->url,
            'response_status' => $log->response_status,
            'status_label' => $this->statusLabel($log->response_status),
            'payload' => $this->presentPayload($log->payload ?? []),
            'old_values' => $this->presentPayload($log->old_values ?? []),
            'new_values' => $this->presentPayload($log->new_values ?? []),
            'changes' => $this->presentChanges($log->old_values ?? [], $log->new_values ?? []),
            'detail_sentence' => $this->detailSentence($log),
            'ip_address' => $log->ip_address,
            'created_at' => $log->created_at?->format('d.m.Y H:i'),
            'created_date' => $log->created_at?->format('d.m.Y'),
            'created_time' => $log->created_at?->format('H:i'),
        ];
    }

    private function actionLabel(AuditLog $log): string
    {
        return $this->humanAction($log);
    }

    private function moduleLabel(?string $routeName): string
    {
        $prefix = str($routeName ?? '')->before('.')->toString();

        return match ($prefix) {
            'customers' => 'Cari',
            'products' => 'Ürün',
            'warehouses' => 'Depo',
            'stock-movements' => 'Stok hareketi',
            'quotes' => 'Teklif',
            'sales' => 'Satış',
            'payments' => 'Tahsilat',
            'cash-accounts' => 'Kasa',
            'settings' => 'Ayarlar',
            'admin' => 'Admin panel',
            'ai' => 'Nexora AI',
            default => 'Sistem',
        };
    }

    private function methodLabel(?string $method): string
    {
        return match ($method) {
            'POST' => 'oluşturma işlemi',
            'PUT', 'PATCH' => 'güncelleme işlemi',
            'DELETE' => 'silme işlemi',
            default => 'işlem',
        };
    }

    private function statusLabel(?int $status): string
    {
        if ($status === null) {
            return 'Bilinmiyor';
        }

        if ($status >= 200 && $status < 300) {
            return 'Başarılı';
        }

        if ($status >= 300 && $status < 400) {
            return 'Yönlendirildi';
        }

        return 'Hata';
    }

    private function presentPayload(array $payload): array
    {
        return collect($payload)
            ->reject(fn ($value, $key) => str_starts_with((string) $key, '_'))
            ->reject(fn ($value, $key) => in_array((string) $key, [
                'items',
                'permissions',
                'password',
                'password_confirmation',
            ], true))
            ->map(fn ($value, $key) => [
                'key' => (string) $key,
                'label' => $this->fieldLabel((string) $key),
                'value' => $this->formatValue($value),
            ])
            ->values()
            ->all();
    }

    private function presentChanges(array $oldValues, array $newValues): array
    {
        return collect($newValues)
            ->map(function ($newValue, $key) use ($oldValues) {
                $oldValue = $oldValues[$key] ?? null;

                if ($this->formatValue($oldValue) === $this->formatValue($newValue)) {
                    return null;
                }

                return [
                    'key' => (string) $key,
                    'label' => $this->fieldLabel((string) $key),
                    'old' => $this->formatValue($oldValue),
                    'new' => $this->formatValue($newValue),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function detailSentence(AuditLog $log): string
    {
        $status = $this->statusLabel($log->response_status);
        $date = $log->created_at?->format('d.m.Y H:i') ?? '-';

        return "{$this->humanAction($log)}. Tarih: {$date}. Sonuç: {$status}.";
    }

    private function humanAction(AuditLog $log): string
    {
        $actor = $log->user?->name ?? 'Sistem';
        $payload = $log->payload ?? [];
        $route = $log->route_name ?? '';
        $method = $log->method;
        $customerName = $this->customerName($payload['customer_id'] ?? null);
        $amount = $this->money($payload['amount'] ?? $payload['grand_total'] ?? null);
        $subject = $this->subjectFromLog($log);

        if (str_starts_with($route, 'payments.') && $method === 'POST') {
            return trim("{$actor}, {$customerName} için {$amount} tahsilat girdi");
        }

        if (str_starts_with($route, 'payments.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return trim("{$actor}, {$customerName} tahsilatını {$amount} olarak güncelledi");
        }

        if (str_starts_with($route, 'payments.') && $method === 'DELETE') {
            return "{$actor}, {$subject} tahsilatını sildi";
        }

        if (str_starts_with($route, 'sales.') && $method === 'POST') {
            return trim("{$actor}, {$customerName} için {$amount} satış oluşturdu");
        }

        if (str_starts_with($route, 'sales.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return "{$actor}, {$subject} satışını güncelledi";
        }

        if (str_starts_with($route, 'sales.') && $method === 'DELETE') {
            return "{$actor}, {$subject} satışını sildi";
        }

        if (str_starts_with($route, 'customers.') && $method === 'POST') {
            return "{$actor}, {$this->payloadName($payload)} carisini oluşturdu";
        }

        if (str_starts_with($route, 'customers.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return "{$actor}, {$this->payloadName($payload, $subject)} carisini güncelledi";
        }

        if (str_starts_with($route, 'customers.') && $method === 'DELETE') {
            return "{$actor}, {$subject} carisini sildi";
        }

        if (str_starts_with($route, 'products.') && $method === 'POST') {
            return "{$actor}, {$this->payloadName($payload)} ürününü oluşturdu";
        }

        if (str_starts_with($route, 'products.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return "{$actor}, {$this->payloadName($payload, $subject)} ürününü güncelledi";
        }

        if (str_starts_with($route, 'products.') && $method === 'DELETE') {
            return "{$actor}, {$subject} ürününü sildi";
        }

        if (str_starts_with($route, 'quotes.') && $method === 'POST') {
            return trim("{$actor}, {$customerName} için {$amount} teklif oluşturdu");
        }

        if (str_starts_with($route, 'support-tickets.') && $method === 'POST') {
            return "{$actor}, {$this->payloadName($payload, 'destek talebi')} oluşturdu";
        }

        if (str_starts_with($route, 'support-tickets.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return "{$actor}, {$subject} destek talebini güncelledi";
        }

        if (str_starts_with($route, 'settings.sub-users.') && $method === 'POST') {
            return "{$actor}, {$this->payloadName($payload)} alt kullanıcısını oluşturdu";
        }

        if (str_starts_with($route, 'settings.sub-users.') && in_array($method, ['PUT', 'PATCH'], true)) {
            return "{$actor}, {$this->payloadName($payload, $subject)} alt kullanıcısını güncelledi";
        }

        if (str_starts_with($route, 'settings.sub-users.') && $method === 'DELETE') {
            return "{$actor}, {$subject} alt kullanıcısını sildi";
        }

        return "{$actor}, {$this->moduleLabel($route)} bölümünde {$this->methodLabel($method)} yaptı";
    }

    private function formatValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'Evet' : 'Hayır';
        }

        if (is_array($value)) {
            $hasNestedValue = collect($value)->contains(fn ($item) => is_array($item) || is_object($item));

            if ($hasNestedValue) {
                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '-';
            }

            return collect($value)
                ->map(fn ($item) => $this->formatValue($item))
                ->implode(', ');
        }

        return (string) $value;
    }

    private function customerName(mixed $customerId): string
    {
        if (! $customerId) {
            return 'cari';
        }

        $customer = Customer::withoutGlobalScopes()->find($customerId);

        if (! $customer) {
            return 'cari';
        }

        return $customer->name ?: ($customer->company ?: "cari #{$customer->id}");
    }

    private function subjectFromLog(AuditLog $log): string
    {
        $parameters = $log->metadata['route_parameters'] ?? [];
        $firstParameter = collect($parameters)->first(fn ($value) => is_array($value) && ! empty($value['label']));

        if ($firstParameter) {
            return $firstParameter['label'];
        }

        $id = collect(explode('/', trim($log->url ?? '', '/')))->last();
        $route = $log->route_name ?? '';

        if (! is_numeric($id)) {
            return 'ilgili kayıt';
        }

        $model = match (true) {
            str_starts_with($route, 'customers.') => Customer::withoutGlobalScopes()->find($id),
            str_starts_with($route, 'products.') => Product::withoutGlobalScopes()->find($id),
            str_starts_with($route, 'sales.') => Sale::withoutGlobalScopes()->find($id),
            str_starts_with($route, 'payments.') => Payment::withoutGlobalScopes()->find($id),
            str_starts_with($route, 'support-tickets.') => SupportTicket::query()->find($id),
            str_starts_with($route, 'settings.sub-users.') => User::query()->find($id),
            default => null,
        };

        return $this->modelLabel($model);
    }

    private function modelLabel(mixed $model): string
    {
        if (! $model) {
            return 'ilgili kayıt';
        }

        foreach (['name', 'company', 'subject', 'sale_no', 'payment_no', 'quote_no', 'code'] as $field) {
            if (! empty($model->{$field})) {
                return (string) $model->{$field};
            }
        }

        return 'ilgili kayıt';
    }

    private function payloadName(array $payload, string $fallback = 'ilgili kayıt'): string
    {
        foreach (['name', 'company', 'subject', 'email', 'code'] as $field) {
            if (! empty($payload[$field])) {
                return (string) $payload[$field];
            }
        }

        return $fallback;
    }

    private function money(mixed $amount): string
    {
        if ($amount === null || $amount === '') {
            return '';
        }

        return '₺' . number_format((float) $amount, 2, ',', '.');
    }

    private function fieldLabel(string $field): string
    {
        return match ($field) {
            'name' => 'Ad',
            'email' => 'E-posta',
            'phone' => 'Telefon',
            'code' => 'Kod',
            'customer_id' => 'Cari',
            'product_id' => 'Ürün',
            'warehouse_id' => 'Depo',
            'sale_date' => 'Satış tarihi',
            'payment_date' => 'Tahsilat tarihi',
            'payment_method' => 'Ödeme yöntemi',
            'amount' => 'Tutar',
            'grand_total' => 'Genel toplam',
            'subtotal' => 'Ara toplam',
            'vat' => 'KDV',
            'discount' => 'İskonto',
            'status' => 'Durum',
            'permissions' => 'Yetkiler',
            'is_active' => 'Aktiflik',
            'subject' => 'Konu',
            'category' => 'Kategori',
            'priority' => 'Öncelik',
            'message' => 'Açıklama',
            'admin_note' => 'Admin notu',
            default => str($field)->replace('_', ' ')->title()->toString(),
        };
    }
}
