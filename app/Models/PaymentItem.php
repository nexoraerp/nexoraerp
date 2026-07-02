<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [

        'payment_id',

        'sale_id',

        'amount',

        'notes',

    ];

    protected $casts = [

        'amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Tahsilat
    |--------------------------------------------------------------------------
    */

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Satış
    |--------------------------------------------------------------------------
    */

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}