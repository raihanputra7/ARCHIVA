<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\SuratKeluar;
use App\Models\User;

class SuratKeluarPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SuratKeluar $suratKeluar): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH, Role::TATA_USAHA, Role::GURU);
    }

    public function update(User $user, SuratKeluar $suratKeluar): bool
    {
        if ($user->hasRole(Role::ADMIN, Role::TATA_USAHA)) {
            return true;
        }

        return $suratKeluar->created_by === $user->id && $suratKeluar->status === 'Draf';
    }

    public function delete(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole(Role::ADMIN, Role::TATA_USAHA);
    }

    public function download(User $user, SuratKeluar $suratKeluar): bool
    {
        return true;
    }

    public function approve(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH);
    }
}
