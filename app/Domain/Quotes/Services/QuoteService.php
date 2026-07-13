<?php

namespace App\Domain\Quotes\Services;

use App\Domain\Sales\Services\SalesService;
use App\Exceptions\BusinessException;
use App\Models\Quote;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public function __construct(
        protected QuoteAnalysisService $analysisService,
        protected SalesService $salesService,
    ) {
    }

    public function create(array $data): Quote
    {
        return DB::transaction(function () use ($data) {
            $prepared = $this->prepare($data);

            $quote = Quote::create([
                'quote_no' => $this->generateQuoteNo(),
                'customer_id' => $prepared['customer_id'],
                'quote_date' => $prepared['quote_date'],
                'valid_until' => $prepared['valid_until'] ?? null,
                'status' => 'Draft',
                'subtotal' => $prepared['totals']['subtotal'],
                'discount' => $prepared['totals']['discount'],
                'vat' => $prepared['totals']['vat'],
                'grand_total' => $prepared['totals']['grand_total'],
                'probability' => $prepared['probability'] ?? 50,
                'notes' => $prepared['notes'] ?? null,
                'terms' => $prepared['terms'] ?? null,
                'user_id' => auth()->id(),
            ]);

            $this->replaceItems($quote, $prepared['items']);

            $quote->update([
                'analysis' => $this->analysisService->analyze($quote->fresh(['customer', 'items.product'])),
            ]);

            return $quote->fresh(['customer', 'items.product', 'items.warehouse', 'user']);
        });
    }

    public function update(Quote $quote, array $data): Quote
    {
        return DB::transaction(function () use ($quote, $data) {
            $this->ensureEditable($quote);

            $prepared = $this->prepare($data);

            $quote->update([
                'customer_id' => $prepared['customer_id'],
                'quote_date' => $prepared['quote_date'],
                'valid_until' => $prepared['valid_until'] ?? null,
                'subtotal' => $prepared['totals']['subtotal'],
                'discount' => $prepared['totals']['discount'],
                'vat' => $prepared['totals']['vat'],
                'grand_total' => $prepared['totals']['grand_total'],
                'probability' => $prepared['probability'] ?? 50,
                'notes' => $prepared['notes'] ?? null,
                'terms' => $prepared['terms'] ?? null,
            ]);

            $this->replaceItems($quote, $prepared['items']);

            $quote->update([
                'analysis' => $this->analysisService->analyze($quote->fresh(['customer', 'items.product'])),
            ]);

            return $quote->fresh(['customer', 'items.product', 'items.warehouse', 'user']);
        });
    }

    public function markAsSent(Quote $quote): Quote
    {
        return $this->changeStatus($quote, 'Sent');
    }

    public function accept(Quote $quote): Quote
    {
        $quote->update([
            'status' => 'Accepted',
            'accepted_at' => now(),
        ]);

        return $quote->fresh();
    }

    public function reject(Quote $quote): Quote
    {
        $quote->update([
            'status' => 'Rejected',
            'rejected_at' => now(),
        ]);

        return $quote->fresh();
    }

    public function refreshAnalysis(Quote $quote): Quote
    {
        $quote->update([
            'analysis' => $this->analysisService->analyze($quote),
        ]);

        return $quote->fresh(['customer', 'items.product', 'items.warehouse', 'user']);
    }

    public function convertToSale(Quote $quote): Sale
    {
        return DB::transaction(function () use ($quote) {
            $quote->loadMissing('items');

            if (! $quote->is_convertible) {
                throw new BusinessException('Bu teklif satışa dönüştürülemez.');
            }

            if ($quote->items->isEmpty()) {
                throw new BusinessException('Kalemsiz teklif satışa dönüştürülemez.');
            }

            $firstItem = $quote->items->first();

            $sale = $this->salesService->create([
                'customer_id' => $quote->customer_id,
                'warehouse_id' => $firstItem->warehouse_id,
                'sale_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'payment_type' => 'Credit',
                'notes' => trim(($quote->notes ?? '')."\n\nTekliften dönüştürüldü: {$quote->quote_no}"),
                'items' => $quote->items->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'warehouse_id' => $item->warehouse_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'vat' => $item->vat,
                ])->values()->all(),
            ]);

            $quote->update([
                'status' => 'Converted',
                'converted_at' => now(),
                'sale_id' => $sale->id,
            ]);

            return $sale;
        });
    }

    protected function changeStatus(Quote $quote, string $status): Quote
    {
        if ($quote->status === 'Converted') {
            throw new BusinessException('Satışa dönüşmüş teklifin durumu değiştirilemez.');
        }

        $quote->update(['status' => $status]);

        return $quote->fresh();
    }

    protected function ensureEditable(Quote $quote): void
    {
        if (in_array($quote->status, ['Converted', 'Cancelled'], true)) {
            throw new BusinessException('Bu teklif düzenlenemez.');
        }
    }

    protected function prepare(array $data): array
    {
        $items = collect($data['items'])->map(function (array $item) use ($data) {
            $quantity = (float) $item['quantity'];
            $unitPrice = (float) $item['unit_price'];
            $discount = (float) ($item['discount'] ?? 0);
            $vat = (float) ($item['vat'] ?? 0);
            $lineTotal = max(0, ($quantity * $unitPrice) - $discount);

            return [
                'product_id' => $item['product_id'],
                'warehouse_id' => $item['warehouse_id'] ?? $data['warehouse_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'vat' => $vat,
                'line_total' => $lineTotal,
                'vat_total' => $lineTotal * ($vat / 100),
            ];
        });

        $subtotal = $items->sum('line_total');
        $vatTotal = $items->sum('vat_total');

        return [
            ...$data,
            'probability' => (int) ($data['probability'] ?? 50),
            'items' => $items->all(),
            'totals' => [
                'subtotal' => $subtotal,
                'discount' => $items->sum('discount'),
                'vat' => $vatTotal,
                'grand_total' => $subtotal + $vatTotal,
            ],
        ];
    }

    protected function replaceItems(Quote $quote, array $items): void
    {
        $quote->items()->delete();

        foreach ($items as $item) {
            $quote->items()->create($item);
        }
    }

    protected function generateQuoteNo(): string
    {
        $lastQuote = Quote::orderByDesc('id')->first();

        if (! $lastQuote || empty($lastQuote->quote_no)) {
            return 'TKL-000001';
        }

        $lastNumber = (int) str_replace('TKL-', '', $lastQuote->quote_no);

        return 'TKL-' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }
}
