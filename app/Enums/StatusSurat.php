<?php

namespace App\Enums;

enum StatusSurat: string
{
    case DITERIMA = 'diterima';
    case DIDISPOSISIKAN = 'didisposisikan';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::DITERIMA => 'Diterima',
            self::DIDISPOSISIKAN => 'Didisposisikan',
            self::DIPROSES => 'Diproses',
            self::SELESAI => 'Selesai',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::DITERIMA => 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
            self::DIDISPOSISIKAN => 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
            self::DIPROSES => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
            self::SELESAI => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
        };
    }

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
