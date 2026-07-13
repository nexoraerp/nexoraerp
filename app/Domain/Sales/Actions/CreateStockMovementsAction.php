<?php

namespace App\Domain\Sales\Actions;

use App\Models\Sale;
use App\Models\StockMovement;

class CreateStockMovementsAction
{
    /**
     * Satışa ait stok hareketlerini oluşturur.
     */
    public function execute(
        Sale $sale,
        array $items
    ): void {

        foreach ($items as $item) {

            StockMovement::create([

                'product_id'     => $item['product_id'],

                'warehouse_id'   => $item['warehouse_id'],

                'type'           => 'OUT',

                'quantity'       => $item['quantity'],

                // İleride gerçek maliyet hesaplanacak.
                'unit_cost'      => $item['unit_price'],

                'reference_type' => Sale::class,

                'reference_id'   => $sale->id,

                'description'    => 'Satış : '.$sale->sale_no,

                'created_by'     => auth()->user()?->tenantOwnerId() ?? auth()->id(),

            ]);

        }

    }
}
