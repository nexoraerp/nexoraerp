<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use BelongsToCurrentUser, HasFactory;

    protected $fillable = [
        'quote_no',
        'customer_id',
        'quote_date',
        'valid_until',
        'status',
        'subtotal',
        'discount',
        'vat',
        'grand_total',
        'probability',
        'analysis',
        'notes',
        'terms',
        'accepted_at',
        'rejected_at',
        'converted_at',
        'sale_id',
        'user_id',
    ];

    protected $casts = [
        'quote_date' => 'date',
        'valid_until' => 'date',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'converted_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'probability' => 'integer',
        'analysis' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsConvertibleAttribute(): bool
    {
        return in_array($this->status, ['Draft', 'Sent', 'Accepted'], true)
            && $this->sale_id === null;
    }
}
