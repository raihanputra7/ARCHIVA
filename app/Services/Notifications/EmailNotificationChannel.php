<?php

namespace App\Services\Notifications;

use App\Models\NotificationLog;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class EmailNotificationChannel
{
    public function channel(): string
    {
        return 'email';
    }

    /**
     * Mengirim email via SMTP (atau log di mode dev jika SMTP belum diset) dengan batas laju per jam.
     * Tidak melempar exception agar tidak memblokir proses utama.
     *
     * @param  array{user_id?: ?int, surat_id?: ?int, disposisi_id?: ?int}  $context
     */
    public function send(string $recipientEmail, Mailable $mailable, array $context = []): bool
    {
        $limit = max(1, (int) config('archiva.mail_hourly_limit', 50));
        $throttleKey = 'archiva:email:hourly';

        if (! app()->runningUnitTests() && RateLimiter::tooManyAttempts($throttleKey, $limit)) {
            $this->recordLog($recipientEmail, 'failed', 'Batas pengiriman email per jam terlampaui.', $context);

            Log::warning('Pengiriman email ditahan karena batas laju per jam tercapai.', [
                'recipient' => $recipientEmail,
                'context' => $context,
            ]);

            return false;
        }

        try {
            $mailerName = $this->resolveMailerName();

            if (! app()->runningUnitTests() && $mailerName === 'log') {
                Log::info('Mode Dev Email (SMTP belum dikonfigurasi): mencatat email ke log.', [
                    'recipient' => $recipientEmail,
                    'mailable' => $mailable::class,
                ]);
            }

            Mail::mailer($mailerName)->to($recipientEmail)->send($mailable);

            if (! app()->runningUnitTests()) {
                RateLimiter::hit($throttleKey, 3600);
            }

            $this->recordLog($recipientEmail, 'sent', null, $context);

            return true;
        } catch (Throwable $e) {
            $this->recordLog($recipientEmail, 'failed', $e->getMessage(), $context);

            Log::error('Gagal mengirim email notifikasi ARCHIVA.', [
                'recipient' => $recipientEmail,
                'mailable' => $mailable::class,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function resolveMailerName(): string
    {
        if (app()->runningUnitTests() || config('mail.default') === 'array') {
            return (string) config('mail.default');
        }

        if (config('archiva.smtp_configured')) {
            return 'smtp';
        }

        return 'log';
    }

    /**
     * @param  array{user_id?: ?int, surat_id?: ?int, disposisi_id?: ?int}  $context
     */
    private function recordLog(string $recipient, string $status, ?string $error, array $context): void
    {
        try {
            NotificationLog::create([
                'user_id' => $context['user_id'] ?? null,
                'surat_id' => $context['surat_id'] ?? null,
                'disposisi_id' => $context['disposisi_id'] ?? null,
                'channel' => $this->channel(),
                'recipient' => $recipient,
                'status' => $status,
                'error' => $error,
                'sent_at' => $status === 'sent' ? now() : null,
            ]);
        } catch (Throwable $e) {
            Log::warning('Gagal menyimpan notification_logs.', ['error' => $e->getMessage()]);
        }
    }
}
