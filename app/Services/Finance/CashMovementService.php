<?php

namespace App\Services\Finance;

use App\Models\CashMovement;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class CashMovementService
{
    /**
     * Tahsilatı Kasaya İşle
     */
    public static function recordCollection(
        Payment $payment,
        Sale $sale,
        float $amount,
        int $cashAccountId = 1
    ): void
    {
        CashMovement::create([

            'cash_account_id' => $cashAccountId,

            'movement_date' => $payment->payment_date,

            'type' => 'COLLECTION',

            'reference_type' => Payment::class,

            'reference_id' => $payment->id,

            'debit' => $amount,

            'credit' => 0,

            'description' => 'Tahsilat : '.$payment->payment_no,

            'user_id' => Auth::id(),

        ]);
    }
}