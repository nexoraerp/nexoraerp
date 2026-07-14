<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'company_name',
    'phone',
    'email',
    'message',
    'status',
    'admin_note',
    'contacted_at',
])]
class LeadRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'contacted_at' => 'datetime',
        ];
    }
}
