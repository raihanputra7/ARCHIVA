<?php

namespace App\Services\Notifications;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Support\PhoneNormalizer;

class WhatsappLinkChannel
{
    public function channel(): string
    {
        return 'whatsapp-link';
    }

    /**
     * Membuat tautan https://wa.me/<nomor>?text=<pesan> untuk penerima disposisi.
     */
    public function buildDisposisiLink(Disposisi $disposisi, ?SuratMasuk $surat = null): ?string
    {
        $recipient = $disposisi->toUser;
        $phone = PhoneNormalizer::normalize($recipient?->phone_wa);

        if ($phone === null) {
            return null;
        }

        $surat ??= $disposisi->suratMasuk;
        $baseUrl = config('archiva.app_base_url', rtrim((string) config('app.url'), '/'));
        $portalLink = $surat ? "{$baseUrl}/surat-masuk/{$surat->id}" : $baseUrl;

        $lines = [
            '*[ARCHIVA SDN Jatipulo 07]*',
            'Yth. '.($recipient->name ?? 'Bapak/Ibu').', Anda menerima disposisi surat masuk:',
            '• Perihal: '.($surat?->perihal ?? '-'),
            '• No. Surat: '.($surat?->nomor_surat_asli ?: ($surat?->nomor_agenda ?? '-')),
            '• Pengirim: '.($surat?->pengirim ?? '-'),
            '• Instruksi: '.($disposisi->instruksi ?? '-'),
        ];

        if ($disposisi->batas_waktu) {
            $lines[] = '• Batas Waktu: '.$disposisi->batas_waktu->isoFormat('D MMM Y');
        }

        $lines[] = 'Buka di portal: '.$portalLink;

        $message = implode("\n", $lines);

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
