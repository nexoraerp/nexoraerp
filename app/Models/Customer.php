<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use BelongsToCurrentUser, HasFactory;

    protected $fillable = [

        'user_id',

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

    public function quotes()
    {
        return $this->hasMany(Quote::class);
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
