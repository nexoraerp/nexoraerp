<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [

        'sale_no',
    
        'customer_id',
    
        'sale_date',
    
        'due_date',
    
        'payment_type',
    
        'subtotal',
    
        'discount',
    
        'vat',
    
        'grand_total',
    
        'payment_status',
    
        'paid_total',
    
        'remaining_total',
    
        'status',
    
        'notes',
    
        'user_id',
    
    ];

    /**
     * Tarih alanları
     */
    protected $casts = [
        'sale_date' => 'date',
        'due_date' => 'date',
    
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
    
        'paid_total' => 'decimal:2',
        'remaining_total' => 'decimal:2',
    ];

    /**
     * Cari
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Kullanıcı
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Satış Kalemleri
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

/*
|--------------------------------------------------------------------------
| Tahsilat Kalemleri
|--------------------------------------------------------------------------
*/

public function paymentItems()
{
    return $this->hasMany(PaymentItem::class);
}

}