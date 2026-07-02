<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [

        'code',

        'name',

        'company',

        'phone',

        'email',

        'tax_number',

        'tax_office',

        'address',

        'type',

        'is_active',

    ];

    /*
    |--------------------------------------------------------------------------
    | Veri Tipleri
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'is_active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Satışlar
    |--------------------------------------------------------------------------
    */

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Tahsilatlar
    |--------------------------------------------------------------------------
    */

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Cari Hareketleri
    |--------------------------------------------------------------------------
    */

    public function movements()
    {
        return $this->hasMany(AccountMovement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Güncel Bakiye
    |--------------------------------------------------------------------------
    */

    public function getBalanceAttribute()
    {
        return $this->movements()->sum('debit')
             - $this->movements()->sum('credit');
    }
}