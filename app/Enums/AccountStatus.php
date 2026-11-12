<?php

namespace App\Enums;

enum AccountStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Persetujuan',
            self::ACTIVE => 'Aktif',
            self::REJECTED => 'Ditolak',
            self::INACTIVE => 'Nonaktif',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PENDING => 'bg-warning-container text-on-warning-container border-warning/30',
            self::ACTIVE => 'bg-success-container text-on-success-container border-success/30',
            self::REJECTED => 'bg-error-container text-on-error-container border-error/30',
            self::INACTIVE => 'bg-surface-container text-on-surface-variant border-outline-variant',
        };
    }
}
