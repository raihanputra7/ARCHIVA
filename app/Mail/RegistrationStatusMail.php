<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly bool $approved,
        public readonly ?string $reason,
        public readonly string $loginUrl,
    ) {}

    public function envelope(): Envelope
    {
        $statusText = $this->approved ? 'Akun Anda Telah Disetujui' : 'Pendaftaran Akun Ditolak';

        return new Envelope(
            subject: "[ARCHIVA] {$statusText} — SDN Jatipulo 07",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-status',
        );
    }
}
