<?php

namespace App\Services\Finance;

use App\Models\AccountMovement;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class AccountMovementService
{
    /**
     * Satış Hareketi
     */
    public static function recordSale(Sale $sale): void
    {
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

            'user_id'        => Auth::id(),

        ]);
    }

    /**
     * Tahsilat Hareketi
     */
    public static function recordPayment(
        Payment $payment,
        Sale $sale,
        float $amount
    ): void
    {
        AccountMovement::create([

            'customer_id'    => $sale->customer_id,

            'movement_date'  => $payment->payment_date,

            'type'           => 'PAYMENT',

            'reference_type' => Payment::class,

            'reference_id'   => $payment->id,

            'debit'          => 0,

            'credit'         => $amount,

            'due_date'       => null,

            'description'    => 'Tahsilat : '.$payment->payment_no,

            'user_id'        => Auth::id(),

        ]);
    }
}