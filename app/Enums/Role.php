<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case KEPALA_SEKOLAH = 'kepala_sekolah';
    case TATA_USAHA = 'tata_usaha';
    case GURU = 'guru';
    case TENAGA_PENDIDIK = 'tenaga_pendidik';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::KEPALA_SEKOLAH => 'Kepala Sekolah',
            self::TATA_USAHA => 'Tata Usaha',
            self::GURU => 'Guru',
            self::TENAGA_PENDIDIK => 'Tenaga Pendidik',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::ADMIN => 'dashboard.admin',
            self::KEPALA_SEKOLAH => 'dashboard.kepala-sekolah',
            self::TATA_USAHA => 'dashboard.tata-usaha',
            self::GURU => 'dashboard.guru',
            self::TENAGA_PENDIDIK => 'dashboard.tenaga-pendidik',
        };
    }
}
