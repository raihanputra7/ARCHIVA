<?php

namespace App\Support;

class PhoneNormalizer
{
    /**
     * Normalisasi nomor WhatsApp Indonesia (08xx / 62xx / +62xx) ke format 62xxxxxxxxxx.
     */
    public static function normalize(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $cleaned = preg_replace('/[^0-9+]/', '', trim($raw)) ?? '';

        if ($cleaned === '') {
            return null;
        }

        if (str_starts_with($cleaned, '+62')) {
            $cleaned = '62'.substr($cleaned, 3);
        } elseif (str_starts_with($cleaned, '0')) {
            $cleaned = '62'.substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62'.$cleaned;
        }

        if (! preg_match('/^62[0-9]{8,13}$/', $cleaned)) {
            return null;
        }

        return $cleaned;
    }

    public static function isValid(?string $raw): bool
    {
        return self::normalize($raw) !== null;
    }
}
