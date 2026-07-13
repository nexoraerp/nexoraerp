<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashAccount extends Model
{
    use BelongsToCurrentUser, HasFactory;

    protected $fillable = [

        'code',

        'name',

        'currency',

        'is_active',

        'notes',

        'user_id',

    ];

    protected $casts = [

        'is_active' => 'boolean',

    ];

    /*
    |--------------------------------------------------------------------------
    | Hareketler
    |--------------------------------------------------------------------------
    */

    public function movements()
    {
        return $this->hasMany(CashMovement::class);
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
