<?php

namespace App\Domain\Sales\Actions;

use App\Models\AccountMovement;
use App\Models\Sale;

class CreateAccountMovementAction
{
    /**
     * Satış sonrası cari hareketi oluşturur.
     */
    public function execute(
        Sale $sale
    ): void {

        AccountMovement::create([

            'customer_id'    => $sale->customer_id,

            'movement_date'  => $sale->sale_date,

            'type'           => 'SALE',

            'reference_type' => Sale::class,

            'reference_id'   => $sale->id,

            'debit'          => $sale->grand_total,

            'credit'         => 0,

            'due_date'       => $sale->due_date,

            'description'    => 'Satış : '.$sale->sale_no,

            'user_id'        => auth()->user()?->tenantOwnerId() ?? auth()->id(),

        ]);

    }
}
