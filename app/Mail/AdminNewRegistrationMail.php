<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $applicant,
        public readonly string $adminUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[ARCHIVA] Pendaftar Baru Menunggu Persetujuan — '.$this->applicant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-registration',
        );
    }
}
