<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToCurrentUser, HasFactory;

    protected $fillable = [
        'user_id',
        'expense_no',
        'expense_category_id',
        'expense_date',
        'due_date',
        'title',
        'description',
        'supplier_name',
        'document_no',
        'subtotal',
        'discount',
        'vat_rate',
        'vat',
        'grand_total',
        'paid_total',
        'remaining_total',
        'payment_status',
        'payment_method',
        'payment_source_type',
        'payment_source_id',
        'status',
        'notes',
        'attachment_path',
        'created_by',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_total' => 'decimal:2',
        'remaining_total' => 'decimal:2',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function paymentSource()
    {
        return $this->morphTo(__FUNCTION__, 'payment_source_type', 'payment_source_id');
    }
}
