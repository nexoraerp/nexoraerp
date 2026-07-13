<?php

namespace App\Domain\Sales\Actions;

use App\Models\Product;
use App\Models\Sale;

class CreateSaleItemsAction
{
    /**
     * Satış Kalemlerini Oluştur
     */
    public function execute(
        Sale $sale,
        array $items
    ): void {

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
}
