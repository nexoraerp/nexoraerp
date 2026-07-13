<?php

namespace App\Http\Controllers;

use App\Models\ProductDefinition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureOwner($request);

        return Inertia::render('Settings/Index', [
            'subUsers' => $request->user()
                ->subUsers()
                ->orderBy('name')
                ->get()
                ->map(fn (User $user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'permissions' => $user->permissions ?? [],
                    'is_active' => $user->is_active,
                ]),
            'permissions' => $this->permissions(),
            'definitions' => ProductDefinition::query()
                ->orderBy('type')
                ->orderBy('name')
                ->get()
                ->groupBy('type')
                ->map(fn ($items) => $items->values())
                ->all(),
            'definitionTypes' => ProductDefinition::types(),
        ]);
    }

    public function storeSubUser(Request $request)
    {
        $this->ensureOwner($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::in(array_keys($this->permissions()))],
            'is_active' => ['boolean'],
        ]);

        User::create([
            'parent_user_id' => $request->user()->tenantOwnerId(),
            'name' => $validated['name'],
            'company_name' => $request->user()->company_name,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => $validated['is_active'] ?? true,
            'trial_started_at' => $request->user()->trial_started_at,
            'trial_ends_at' => $request->user()->trial_ends_at,
            'license_started_at' => $request->user()->license_started_at,
            'license_ends_at' => $request->user()->license_ends_at,
        ]);

        return back()->with('success', 'Alt kullanıcı oluşturuldu.');
    }

    public function updateSubUser(Request $request, User $user)
    {
        $this->ensureOwner($request);
        $this->ensureOwnSubUser($request, $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8'],
            'permissions' => ['array'],
            'permissions.*' => ['string', Rule::in(array_keys($this->permissions()))],
            'is_active' => ['boolean'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'permissions' => $validated['permissions'] ?? [],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Alt kullanıcı güncellendi.');
    }

    public function destroySubUser(Request $request, User $user)
    {
        $this->ensureOwner($request);
        $this->ensureOwnSubUser($request, $user);

        $user->delete();

        return back()->with('success', 'Alt kullanıcı silindi.');
    }

    public function storeDefinition(Request $request)
    {
        $this->ensureOwner($request);

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(ProductDefinition::types()))],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_definitions', 'name')
                    ->where('user_id', $request->user()->tenantOwnerId())
                    ->where('type', $request->input('type')),
            ],
        ]);

        ProductDefinition::create([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Tanım eklendi.');
    }

    public function destroyDefinition(Request $request, ProductDefinition $definition)
    {
        $this->ensureOwner($request);

        $definition->delete();

        return back()->with('success', 'Tanım silindi.');
    }

    private function permissions(): array
    {
        return [
            'customers' => 'Cari Yönetimi',
            'quotes' => 'Teklifler',
            'sales' => 'Satışlar',
            'payments' => 'Tahsilatlar',
            'products' => 'Ürünler',
            'warehouses' => 'Depolar',
            'stock' => 'Stok Hareketleri',
            'finance' => 'Finans / Kasa',
            'expenses.view' => 'Giderleri Görüntüle',
            'expenses.create' => 'Gider Oluştur',
            'expenses.update' => 'Gider Güncelle',
            'expenses.cancel' => 'Gider İptal Et',
            'expenses.approve' => 'Gider Onayla',
            'expenses.view_vat' => 'Gider KDV Bilgisi',
            'expenses.view_profit' => 'Kâr ve Maliyet Bilgisi',
            'profit_loss.view' => 'Kâr/Zarar Raporu',
            'profit_loss.export' => 'Kâr/Zarar Dışa Aktar',
            'reports' => 'Raporlar ve Risk Analizi',
            'support' => 'Çözüm Öneri ve Destek',
            'settings' => 'Yönetim ve Ayarlar',
        ];
    }

    private function ensureOwner(Request $request): void
    {
        abort_if($request->user()?->parent_user_id !== null, 403, 'Ayarları yalnızca ana kullanıcı yönetebilir.');
    }

    private function ensureOwnSubUser(Request $request, User $user): void
    {
        abort_if((int) $user->parent_user_id !== (int) $request->user()->tenantOwnerId(), 403);
    }
}
