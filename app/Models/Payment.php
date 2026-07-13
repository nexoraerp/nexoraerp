<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToCurrentUser, HasFactory;

    protected $fillable = [

        'payment_no',

        'customer_id',

        'payment_date',

        'amount',

        'payment_method',

        'reference_no',

        'notes',

        'user_id',

    ];

    protected $casts = [

        'payment_date' => 'date',

        'amount' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Cari
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Oluşturan Kullanıcı
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
|--------------------------------------------------------------------------
| Tahsilat Kalemleri
|--------------------------------------------------------------------------
*/

public function items()
{
    return $this->hasMany(PaymentItem::class);
}
/*
|--------------------------------------------------------------------------
| Toplam Tahsilat Tutarı
|--------------------------------------------------------------------------
*/

public function getTotalAmountAttribute()
{
    return $this->items->sum('amount');
}
}
