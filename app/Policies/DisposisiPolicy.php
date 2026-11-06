<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Disposisi;
use App\Models\User;

class DisposisiPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Disposisi $disposisi): bool
    {
        if ($user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH)) {
            return true;
        }

        return $disposisi->to_user_id === $user->id || $disposisi->from_user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH);
    }

    public function updateStatus(User $user, Disposisi $disposisi): bool
    {
        // Penerima disposisi, atau pimpinan / admin
        if ($disposisi->to_user_id === $user->id) {
            return true;
        }

        return $user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH);
    }
}
