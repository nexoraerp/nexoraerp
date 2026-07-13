<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'parent_user_id',
    'company_name',
    'email',
    'phone',
    'password',
    'role',
    'permissions',
    'is_active',
    'trial_started_at',
    'trial_ends_at',
    'license_started_at',
    'license_ends_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
            'trial_started_at' => 'datetime',
            'trial_ends_at' => 'datetime',
            'license_started_at' => 'datetime',
            'license_ends_at' => 'datetime',
        ];
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function onboardingProgress()
    {
        return $this->hasOne(OnboardingProgress::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }

    public function subUsers()
    {
        return $this->hasMany(User::class, 'parent_user_id');
    }

    public function tenantOwnerId(): int
    {
        return (int) ($this->parent_user_id ?: $this->id);
    }

    public function canPerform(string $permission): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->role === 'admin') {
            return true;
        }

        if ($this->parent_user_id === null) {
            return true;
        }

        return in_array($permission, $this->permissions ?? [], true);
    }
}
