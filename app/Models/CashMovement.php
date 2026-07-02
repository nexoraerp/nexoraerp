<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [

        'cash_account_id',

        'movement_date',

        'type',

        'reference_type',

        'reference_id',

        'debit',

        'credit',

        'description',

        'user_id',

    ];

    protected $casts = [

        'movement_date' => 'date',

        'debit' => 'decimal:2',

        'credit' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Kasa
    |--------------------------------------------------------------------------
    */

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Kullanıcı
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Referans
    |--------------------------------------------------------------------------
    */

    public function reference()
    {
        return $this->morphTo();
    }
}