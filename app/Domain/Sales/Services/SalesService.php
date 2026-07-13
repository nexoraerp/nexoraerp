<?php

namespace App\Domain\Sales\Services;

use App\Actions\Onboarding\CompleteFirstSaleTaskAction;
use App\Domain\Sales\Calculators\SaleCalculator;
use App\Domain\Sales\Validators\SaleValidator;
use App\Exceptions\BusinessException;
use App\Models\AccountMovement;
use App\Domain\Sales\Workflows\CreateSaleWorkflow;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class SalesService
{
    public function __construct(
        protected CreateSaleWorkflow $workflow,
        protected SaleValidator $validator,
        protected SaleCalculator $calculator,
        protected CompleteFirstSaleTaskAction $completeFirstSaleTask,
    ) {
    }

    /**
     * Yeni satış oluştur.
     */
    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {

            $sale = $this->workflow->handle(
                $this->normalizeDates($data)
            );

            $this->completeFirstSaleTask->execute(auth()->user());

            return $sale;

        });
    }

    public function update(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {

            $this->ensureEditable($sale);

            $prepared = $this->calculator->calculate(
                $this->validator->validate($this->normalizeDates($data))
            );

            $totals = $prepared['totals'];

            $sale->update([
                'customer_id' => $prepared['customer_id'],
                'sale_date' => $prepared['sale_date'],
                'due_date' => $prepared['due_date'],
                'payment_type' => $prepared['payment_type'],
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'vat' => $totals['vat'],
                'grand_total' => $totals['grand_total'],
                'remaining_total' => max(
                    0,
                    $totals['grand_total'] - (float) $sale->paid_total
                ),
                'payment_status' => $this->resolvePaymentStatus(
                    (float) $sale->paid_total,
                    $totals['grand_total']
                ),
                'notes' => $prepared['notes'] ?? null,
            ]);

            $this->replaceItems($sale, $prepared['items']);
            $this->replaceStockMovements($sale, $prepared['items']);
            $this->syncAccountMovement($sale);

            return $sale->fresh([
                'customer',
                'items.product',
                'items.warehouse',
                'user',
            ]);
        });
    }

    public function cancel(Sale $sale, string $reason): Sale
    {
        return DB::transaction(function () use ($sale, $reason) {

            if ($sale->status === 'Cancelled') {
                throw new BusinessException('Bu satış zaten iptal edilmiş.');
            }

            if ((float) $sale->paid_total > 0) {
                throw new BusinessException('Tahsilatı olan satış iptal edilemez.');
            }

            $sale->loadMissing('items');

            $sale->update([
                'status' => 'Cancelled',
                'payment_status' => 'Unpaid',
                'remaining_total' => 0,
                'cancel_reason' => $reason,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
            ]);

            foreach ($sale->items as $item) {
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $item->warehouse_id,
                    'type' => 'IN',
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_price,
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'description' => 'Satış iptal edildi - '.$sale->sale_no,
                    'created_by' => auth()->user()?->tenantOwnerId() ?? auth()->id(),
                ]);
            }

            $this->syncAccountMovement($sale, true);

            return $sale->fresh([
                'customer',
                'items.product',
                'items.warehouse',
                'user',
            ]);
        });
    }

    protected function ensureEditable(Sale $sale): void
    {
        if ($sale->status === 'Cancelled') {
            throw new BusinessException('İptal edilmiş satış düzenlenemez.');
        }
    }

    protected function normalizeDates(array $data): array
    {
        if (($data['payment_type'] ?? null) === 'Cash') {
            $data['due_date'] = $data['sale_date'] ?? null;
        }

        return $data;
    }

    protected function replaceItems(Sale $sale, array $items): void
    {
        $sale->items()->delete();

        foreach ($items as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'warehouse_id' => $item['warehouse_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'purchase_price_snapshot' => Product::query()
                    ->whereKey($item['product_id'])
                    ->value('purchase_price') ?? 0,
                'discount' => $item['discount'] ?? 0,
                'vat' => $item['vat'] ?? 0,
                'line_total' => $item['line_total'],
            ]);
        }
    }

    protected function replaceStockMovements(Sale $sale, array $items): void
    {
        StockMovement::where('reference_type', Sale::class)
            ->where('reference_id', $sale->id)
            ->where('type', 'OUT')
            ->delete();

        foreach ($items as $item) {
            StockMovement::create([
                'product_id' => $item['product_id'],
                'warehouse_id' => $item['warehouse_id'],
                'type' => 'OUT',
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_price'],
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'description' => 'Satış güncellendi - '.$sale->sale_no,
                'created_by' => auth()->user()?->tenantOwnerId() ?? auth()->id(),
            ]);
        }
    }

    protected function syncAccountMovement(Sale $sale, bool $cancelled = false): void
    {
        AccountMovement::updateOrCreate(
            [
                'reference_type' => Sale::class,
                'reference_id' => $sale->id,
                'type' => 'SALE',
            ],
            [
                'customer_id' => $sale->customer_id,
                'movement_date' => $sale->sale_date,
                'debit' => $cancelled ? 0 : $sale->grand_total,
                'credit' => 0,
                'due_date' => $sale->due_date,
                'description' => $cancelled
                    ? 'Satış iptal edildi : '.$sale->sale_no
                    : 'Satış : '.$sale->sale_no,
                'user_id' => auth()->user()?->tenantOwnerId() ?? auth()->id(),
            ]
        );
    }

    protected function resolvePaymentStatus(float $paidTotal, float $grandTotal): string
    {
        if ($paidTotal <= 0) {
            return 'Unpaid';
        }

        if ($paidTotal >= $grandTotal) {
            return 'Paid';
        }

        return 'Partial';
    }
}
