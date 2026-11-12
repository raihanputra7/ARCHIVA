@component('emails.layout', ['title' => 'Verifikasi Alamat Email'])
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;color:#1B3A6B;">
        Verifikasi Alamat Email Anda
    </h2>
    <p style="margin:0 0 14px;">
        Halo <strong>{{ $user->name }}</strong>,
    </p>
    <p style="margin:0 0 16px;">
        Terima kasih telah mendaftar di portal persuratan <strong>ARCHIVA SDN Jatipulo 07</strong>.
        Untuk membuktikan alamat email Anda aktif dan agar pendaftaran dapat diproses oleh Administrator, silakan klik tombol di bawah ini:
    </p>
    <div style="margin:24px 0;text-align:center;">
        <a href="{{ $verifyUrl }}" style="display:inline-block;background-color:#1B3A6B;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:10px;border-bottom:3px solid #C9A84C;">
            Verifikasi Email Sekarang
        </a>
    </div>
    <p style="margin:0 0 10px;font-size:12px;color:#475569;">
        Tautan verifikasi ini berlaku selama <strong>24 jam</strong> dan hanya dapat digunakan <strong>satu kali</strong>.
    </p>
    <p style="margin:0;font-size:12px;color:#64748B;word-break:break-all;">
        Jika tombol tidak berfungsi, salin dan buka tautan berikut di browser:<br>
        <a href="{{ $verifyUrl }}" style="color:#1B3A6B;">{{ $verifyUrl }}</a>
    </p>
@endcomponent

