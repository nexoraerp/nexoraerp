<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMovement extends Model
{
    use BelongsToCurrentUser, HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Tablo Adı
    |--------------------------------------------------------------------------
    */

    protected $table = 'customer_movements';

    protected $fillable = [

        'customer_id',

        'movement_date',

        'type',

        'reference_type',

        'reference_id',

        'debit',

        'credit',

        'due_date',

        'description',

        'user_id',

    ];

    protected $casts = [

        'movement_date' => 'date',

        'due_date' => 'date',

        'debit' => 'decimal:2',

        'credit' => 'decimal:2',

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
    | Kullanıcı
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Referans (Sale, Payment vb.)
    |--------------------------------------------------------------------------
    */

    public function reference()
    {
        return $this->morphTo();
    }
}
