<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_user_id',
        'ticket_no',
        'subject',
        'category',
        'priority',
        'status',
        'message',
        'admin_note',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenantOwner()
    {
        return $this->belongsTo(User::class, 'tenant_user_id');
    }

    public function scopeForTenant($query, User $user)
    {
        return $query->where('tenant_user_id', $user->tenantOwnerId());
    }
}
