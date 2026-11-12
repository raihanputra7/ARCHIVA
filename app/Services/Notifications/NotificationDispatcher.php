<?php

namespace App\Services\Notifications;

use App\Mail\AdminNewRegistrationMail;
use App\Mail\DisposisiCreatedMail;
use App\Mail\RegistrationStatusMail;
use App\Mail\VerifyEmailMail;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationDispatcher
{
    public function __construct(
        private readonly EmailNotificationChannel $emailChannel,
        private readonly WhatsappLinkChannel $whatsappChannel,
    ) {}

    public function sendVerificationEmail(User $user, string $plainToken): bool
    {
        $baseUrl = config('archiva.app_base_url', rtrim((string) config('app.url'), '/'));
        $verifyUrl = "{$baseUrl}/verifikasi-email/{$plainToken}";

        return $this->emailChannel->send(
            $user->email,
            new VerifyEmailMail($user, $verifyUrl),
            ['user_id' => $user->id]
        );
    }

    public function notifyAdminNewRegistration(User $applicant): void
    {
        $adminEmail = trim((string) config('archiva.admin_notify_email', ''));
        if ($adminEmail === '') {
            return;
        }

        $baseUrl = config('archiva.app_base_url', rtrim((string) config('app.url'), '/'));
        $adminUrl = "{$baseUrl}/dashboard/admin/permintaan-pendaftaran";

        dispatch(function () use ($adminEmail, $applicant, $adminUrl) {
            $this->emailChannel->send(
                $adminEmail,
                new AdminNewRegistrationMail($applicant, $adminUrl),
                ['user_id' => $applicant->id]
            );
        })->afterResponse();
    }

    public function notifyRegistrationDecision(User $user, bool $approved, ?string $reason = null): void
    {
        $baseUrl = config('archiva.app_base_url', rtrim((string) config('app.url'), '/'));
        $loginUrl = "{$baseUrl}/login";

        dispatch(function () use ($user, $approved, $reason, $loginUrl) {
            $this->emailChannel->send(
                $user->email,
                new RegistrationStatusMail($user, $approved, $reason, $loginUrl),
                ['user_id' => $user->id]
            );
        })->afterResponse();
    }

    /**
     * Mengirim notifikasi disposisi secara async setelah response agar tidak memblokir transaksi.
     *
     * @param  Collection<int, Disposisi>  $disposisiList
     */
    public function dispatchDisposisiCreated(SuratMasuk $surat, Collection $disposisiList): void
    {
        if ($disposisiList->isEmpty()) {
            return;
        }

        $disposisiIds = $disposisiList->pluck('id')->all();
        $suratId = $surat->id;

        dispatch(function () use ($suratId, $disposisiIds) {
            $surat = SuratMasuk::find($suratId);
            if (! $surat) {
                return;
            }

            $items = Disposisi::with(['toUser', 'fromUser'])
                ->whereIn('id', $disposisiIds)
                ->get();

            $baseUrl = config('archiva.app_base_url', rtrim((string) config('app.url'), '/'));
            $portalUrl = "{$baseUrl}/surat-masuk/{$surat->id}";

            foreach ($items as $disp) {
                $recipient = $disp->toUser;
                if (! $recipient || ! $recipient->email) {
                    continue;
                }

                if (! ($recipient->notify_email ?? true)) {
                    continue;
                }

                $this->emailChannel->send(
                    $recipient->email,
                    new DisposisiCreatedMail($surat, $disp, $recipient, $portalUrl),
                    [
                        'user_id' => $recipient->id,
                        'surat_id' => $surat->id,
                        'disposisi_id' => $disp->id,
                    ]
                );
            }
        })->afterResponse();
    }

    public function whatsappLinkForDisposisi(Disposisi $disposisi, ?SuratMasuk $surat = null): ?string
    {
        return $this->whatsappChannel->buildDisposisiLink($disposisi, $surat);
    }
}
