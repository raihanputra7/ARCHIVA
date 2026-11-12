<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\AccountStatus;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'phone_wa',
    'password',
    'role',
    'requested_role',
    'unit',
    'status',
    'rejected_reason',
    'approved_by',
    'approved_at',
    'notify_email',
    'email_verified_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
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
            'approved_at' => 'datetime',
            'notify_email' => 'boolean',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'approved_by');
    }

    public function isActive(): bool
    {
        return ($this->status ?? AccountStatus::ACTIVE->value) === AccountStatus::ACTIVE->value;
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole(string|Role ...$roles): bool
    {
        $currentRole = $this->role instanceof Role ? $this->role->value : (string) $this->role;

        foreach ($roles as $role) {
            $expectedRole = $role instanceof Role ? $role->value : (string) $role;
            if ($currentRole === $expectedRole) {
                return true;
            }
        }

        return false;
    }
}
