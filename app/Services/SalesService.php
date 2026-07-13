<?php

namespace App\Services;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Services\Sales\Actions\CalculateSaleTotalsAction;

class SalesService extends BaseService
{
    public function __construct()
    {
        $this->model = new Sale();
    }

    /*
    |--------------------------------------------------------------------------
    | Satış Oluştur
    |--------------------------------------------------------------------------
    */

    public function create(array $data): Sale
    {
        return DB::transaction(function () use ($data) {

            $totals = $this->calculateTotals(
                $data['items']
            );

            $sale = $this->createSale(
                $data,
                $totals
            );

            $this->createItems(
                $sale,
                $data['items']
            );

            $this->createStockMovements(
                $sale,
                $data['items']
            );

            $this->createAccountMovement(
                $sale
            );

            return $sale->fresh([
                'customer',
                'items.product',
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Toplam Hesapla
    |--------------------------------------------------------------------------
    */

    protected function calculateTotals(array $items): array
    {
        $subtotal = 0;
        $discount = 0;
        $vat = 0;

        foreach ($items as $item) {

            $quantity = (float) ($item['quantity'] ?? 0);

            $unitPrice = (float) ($item['unit_price'] ?? 0);

            $lineDiscount = (float) ($item['discount'] ?? 0);

            $lineVat = (float) ($item['vat'] ?? 0);

            $lineSubtotal =
                ($quantity * $unitPrice)
                - $lineDiscount;

            $subtotal += $lineSubtotal;

            $discount += $lineDiscount;

            $vat +=
                $lineSubtotal
                * ($lineVat / 100);

        }

        return [

            'subtotal' => round($subtotal, 2),

            'discount' => round($discount, 2),

            'vat' => round($vat, 2),

            'grand_total' => round(
                $subtotal + $vat,
                2
            ),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Satış Oluştur
    |--------------------------------------------------------------------------
    */

    protected function createSale(
        array $data,
        array $totals
    ): Sale
    {
        throw new \RuntimeException(
            'createSale() henüz uygulanmadı.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Satış Kalemleri
    |--------------------------------------------------------------------------
    */

    protected function createItems(
        Sale $sale,
        array $items
    ): void {

        //
    }

    /*
    |--------------------------------------------------------------------------
    | Stok Hareketleri
    |--------------------------------------------------------------------------
    */

    protected function createStockMovements(
        Sale $sale,
        array $items
    ): void {

        //
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Hareketi
    |--------------------------------------------------------------------------
    */

    protected function createAccountMovement(
        Sale $sale
    ): void {

        //
    }
}