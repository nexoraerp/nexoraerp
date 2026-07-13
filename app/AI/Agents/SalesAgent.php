<?php

namespace App\AI\Agents;

use App\AI\Conversation\ConversationState;
use App\AI\Core\Agent;
use App\AI\Core\BaseAgent;
use App\AI\Core\Intent;
use App\AI\Core\Workflow;
use App\AI\Core\WorkflowEngine;
use App\AI\Core\WorkflowStep;
use App\Domain\Sales\Services\SalesService;
use App\Exceptions\BusinessException;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use Carbon\Carbon;

#[Agent('sales')]
#[Intent('sale.create')]
class SalesAgent extends BaseAgent
{
    public function __construct(
        ConversationState $state,
        WorkflowEngine $engine,
        protected SalesService $salesService
    ) {
        parent::__construct($state, $engine);
    }

    public function workflow(): Workflow
    {
        return new Workflow([

            new WorkflowStep(
                key: 'customer',
                question: 'Satışı hangi cariye oluşturalım? Cari adı veya kodunu yaz.',
                field: 'customer'
            ),

            new WorkflowStep(
                key: 'product',
                question: 'Hangi ürünü satıyoruz? Ürün adı, kodu veya barkodunu yaz.',
                field: 'product'
            ),

            new WorkflowStep(
                key: 'quantity',
                question: 'Kaç adet satılacak?',
                field: 'quantity'
            ),

            new WorkflowStep(
                key: 'warehouse',
                question: 'Hangi depodan çıkış yapılacak? Depo adı veya kodunu yaz.',
                field: 'warehouse'
            ),

            new WorkflowStep(
                key: 'payment_type',
                question: 'Ödeme tipi nedir? Nakit, vadeli, kart, banka veya karma yazabilirsin.',
                field: 'payment_type'
            ),

            new WorkflowStep(
                key: 'due_date',
                question: 'Vade tarihi nedir? Bugün, yarın, bir ay sonra veya 2026-07-20 gibi yazabilirsin.',
                field: 'due_date'
            ),

            new WorkflowStep(
                key: 'notes',
                question: 'Satış notu var mı? Yoksa "yok" yaz.',
                field: 'notes'
            ),

        ]);
    }

    public function finish(array $data): array
    {
        try {
            $customer = $this->findCustomer($data['customer'] ?? '');
            $product = $this->findProduct($data['product'] ?? '');
            $warehouse = $this->findWarehouse($data['warehouse'] ?? '');
            $quantity = $this->parseQuantity($data['quantity'] ?? '');
            $paymentType = $this->parsePaymentType($data['payment_type'] ?? '');
            $saleDate = now()->toDateString();
            $dueDate = $this->parseDate($data['due_date'] ?? '', $saleDate);

            if (! $customer) {
                return $this->error('Cari bulunamadı. Cari adı veya kodunu kontrol edelim.');
            }

            if (! $product) {
                return $this->error('Ürün bulunamadı. Ürün adı, kodu veya barkodu ile tekrar deneyelim.');
            }

            if (! $warehouse) {
                return $this->error('Depo bulunamadı. Depo adı veya kodunu kontrol edelim.');
            }

            if ($quantity <= 0) {
                return $this->error("Miktar 0'dan büyük olmalı.");
            }

            if ($paymentType === null) {
                return $this->error('Ödeme tipini anlayamadım. Nakit, vadeli, kart, banka veya karma yazmalısın.');
            }

            if ((float) $product->current_stock < $quantity) {
                return $this->error(
                    "Stok yetersiz. {$product->name} için mevcut stok: {$product->current_stock}."
                );
            }

            $sale = $this->salesService->create([
                'customer_id' => $customer->id,
                'warehouse_id' => $warehouse->id,
                'sale_date' => $saleDate,
                'due_date' => $paymentType === 'Cash' ? $saleDate : $dueDate,
                'payment_type' => $paymentType,
                'notes' => $this->normalizeNotes($data['notes'] ?? null),
                'items' => [
                    [
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                        'quantity' => $quantity,
                        'unit_price' => (float) $product->sale_price,
                        'discount' => 0,
                        'vat' => (float) $product->vat,
                    ],
                ],
            ]);

            return [
                'success' => true,
                'action' => 'save',
                'message' => "Satış {$sale->sale_no} başarıyla oluşturuldu.",
                'data' => [
                    'id' => $sale->id,
                    'sale_no' => $sale->sale_no,
                    'customer' => $customer->name,
                    'product' => $product->name,
                    'quantity' => $quantity,
                    'grand_total' => $sale->grand_total,
                    'sale' => $sale,
                ],
            ];
        } catch (BusinessException $exception) {
            return $this->error($exception->getMessage());
        } catch (\Throwable $exception) {
            return $this->error('Satış oluşturulurken hata oluştu: '.$exception->getMessage());
        }
    }

    private function findCustomer(string $search): ?Customer
    {
        $search = trim($search);

        return Customer::query()
            ->where('code', $search)
            ->orWhere('name', 'like', "%{$search}%")
            ->orWhere('company', 'like', "%{$search}%")
            ->first();
    }

    private function findProduct(string $search): ?Product
    {
        $search = trim($search);

        return Product::query()
            ->where('code', $search)
            ->orWhere('barcode', $search)
            ->orWhere('name', 'like', "%{$search}%")
            ->first();
    }

    private function findWarehouse(string $search): ?Warehouse
    {
        $search = trim($search);

        return Warehouse::query()
            ->where('code', $search)
            ->orWhere('name', 'like', "%{$search}%")
            ->first();
    }

    private function parseQuantity(string $value): float
    {
        return (float) str_replace(',', '.', trim($value));
    }

    private function parsePaymentType(string $value): ?string
    {
        $value = mb_strtolower(trim($value));

        return match (true) {
            str_contains($value, 'nakit'), str_contains($value, 'cash') => 'Cash',
            str_contains($value, 'vadeli'), str_contains($value, 'kredi'), str_contains($value, 'credit') => 'Credit',
            str_contains($value, 'kart'), str_contains($value, 'card') => 'Card',
            str_contains($value, 'banka'), str_contains($value, 'havale'), str_contains($value, 'eft'), str_contains($value, 'bank') => 'Bank',
            str_contains($value, 'karma'), str_contains($value, 'mixed') => 'Mixed',
            default => null,
        };
    }

    private function parseDate(string $value, string $fallback): string
    {
        $value = mb_strtolower(trim($value));

        if (in_array($value, ['', 'yok', 'bugün', 'bugun'], true)) {
            return $fallback;
        }

        if (in_array($value, ['yarın', 'yarin'], true)) {
            return now()->addDay()->toDateString();
        }

        $relativeDate = $this->parseTurkishRelativeDate($value);

        if ($relativeDate !== null) {
            return $relativeDate;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            throw new BusinessException(
                'Vade tarihini anlayamadım. Örn: bugün, yarın, bir ay sonra, 15 gün sonra veya 2026-07-20 yazabilirsin.'
            );
        }
    }

    private function parseTurkishRelativeDate(string $value): ?string
    {
        $numberWords = [
            'bir' => 1,
            'iki' => 2,
            'üç' => 3,
            'uc' => 3,
            'dört' => 4,
            'dort' => 4,
            'beş' => 5,
            'bes' => 5,
            'altı' => 6,
            'alti' => 6,
            'yedi' => 7,
            'sekiz' => 8,
            'dokuz' => 9,
            'on' => 10,
        ];

        if (! preg_match('/^(\d+|[[:alpha:]ğüşöçıİĞÜŞÖÇ]+)\s+(gün|gun|hafta|ay|yıl|yil)(\s+sonra)?$/u', $value, $matches)) {
            return null;
        }

        $amount = is_numeric($matches[1])
            ? (int) $matches[1]
            : ($numberWords[$matches[1]] ?? null);

        if ($amount === null || $amount <= 0) {
            return null;
        }

        return match ($matches[2]) {
            'gün', 'gun' => now()->addDays($amount)->toDateString(),
            'hafta' => now()->addWeeks($amount)->toDateString(),
            'ay' => now()->addMonthsNoOverflow($amount)->toDateString(),
            'yıl', 'yil' => now()->addYearsNoOverflow($amount)->toDateString(),
            default => null,
        };
    }

    private function normalizeNotes(?string $notes): ?string
    {
        $notes = trim((string) $notes);

        return in_array(mb_strtolower($notes), ['', 'yok', 'hayır', 'hayir'], true)
            ? null
            : $notes;
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'action' => 'error',
            'message' => $message,
        ];
    }
}
