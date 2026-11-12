<?php

namespace App\Mail;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DisposisiCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly SuratMasuk $surat,
        public readonly Disposisi $disposisi,
        public readonly User $recipient,
        public readonly string $portalUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[ARCHIVA] Disposisi Surat Baru: '.$this->surat->perihal,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.disposisi-created',
        );
    }
}
