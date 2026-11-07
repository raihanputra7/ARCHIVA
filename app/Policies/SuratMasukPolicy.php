<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\SuratMasuk;
use App\Models\User;

class SuratMasukPolicy
{
    /**
     * Admin tidak boleh melihat arsip surat dinas (khusus tata kelola pengguna).
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return false;
        }

        return $user->hasRole(
            Role::KEPALA_SEKOLAH,
            Role::TATA_USAHA,
            Role::GURU,
            Role::TENAGA_PENDIDIK
        );
    }

    /**
     * Akses membaca surat:
     * - Admin ditolak.
     * - Kepala Sekolah & Tata Usaha melihat semua surat.
     * - Guru & Tenaga Pendidik hanya melihat surat jika mereka terdaftar di penerima atau penerima disposisi.
     */
    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        if ($user->hasRole(Role::ADMIN)) {
            return false;
        }

        if ($user->hasRole(Role::KEPALA_SEKOLAH, Role::TATA_USAHA)) {
            return true;
        }

        // Cek tabel pivot penerima (surat_penerima)
        $isPenerima = $suratMasuk->penerima()->where('user_id', $user->id)->exists();
        if ($isPenerima) {
            return true;
        }

        // Cek tabel disposisi
        return $suratMasuk->disposisi()
            ->where(function ($query) use ($user) {
                $query->where('ke_user_id', $user->id)
                    ->orWhere('to_user_id', $user->id);
            })
            ->exists();
    }

    /**
     * Pencatatan surat masuk hanya boleh dilakukan oleh Tata Usaha.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(Role::TATA_USAHA);
    }

    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole(Role::TATA_USAHA);
    }

    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole(Role::TATA_USAHA);
    }

    public function download(User $user, SuratMasuk $suratMasuk): bool
    {
        return $this->view($user, $suratMasuk);
    }

    public function createDisposisi(User $user, SuratMasuk $suratMasuk): bool
    {
        // Pimpinan (Kepsek), TU, dan Guru berwenang membuat/meneruskan instruksi disposisi
        if (! $this->view($user, $suratMasuk)) {
            return false;
        }

        return $user->hasRole(
            Role::KEPALA_SEKOLAH,
            Role::TATA_USAHA,
            Role::GURU
        );
    }
}
