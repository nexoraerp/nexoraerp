<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingProgress extends Model
{
    use HasFactory;

    protected $table = 'onboarding_progress';

    protected $fillable = [
        'user_id',
        'first_customer_completed_at',
        'first_product_completed_at',
        'first_sale_completed_at',
        'first_payment_completed_at',
        'completed_at',
    ];

    protected $casts = [
        'first_customer_completed_at' => 'datetime',
        'first_product_completed_at' => 'datetime',
        'first_sale_completed_at' => 'datetime',
        'first_payment_completed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
