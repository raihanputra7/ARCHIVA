<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\SuratMasuk;
use App\Models\User;

class SuratMasukPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        if ($user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH, Role::TATA_USAHA)) {
            return true;
        }

        // Guru & Tendik hanya dapat melihat surat yang didisposisikan kepada mereka
        return $suratMasuk->disposisi()->where('to_user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::ADMIN, Role::TATA_USAHA);
    }

    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole(Role::ADMIN, Role::TATA_USAHA);
    }

    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole(Role::ADMIN, Role::TATA_USAHA);
    }

    public function download(User $user, SuratMasuk $suratMasuk): bool
    {
        return $this->view($user, $suratMasuk);
    }

    public function createDisposisi(User $user, SuratMasuk $suratMasuk): bool
    {
        // Hanya Kepala Sekolah (dan Admin) yang berhak memberikan instruksi disposisi
        return $user->hasRole(Role::ADMIN, Role::KEPALA_SEKOLAH);
    }
}
