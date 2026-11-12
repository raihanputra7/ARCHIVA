@component('emails.layout', ['title' => 'Pendaftar Baru Menunggu Persetujuan'])
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;color:#1B3A6B;">
        Pendaftar Baru Menunggu Persetujuan
    </h2>
    <p style="margin:0 0 14px;">
        Administrator Yth., terdapat pengajuan akun baru yang telah memverifikasi alamat emailnya dan siap ditinjau:
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:13px;">
        <tr>
            <td style="padding:4px 0;color:#64748B;width:140px;">Nama Lengkap</td>
            <td style="padding:4px 0;font-weight:700;color:#0F172A;">{{ $applicant->name }}</td>
        </tr>
        <tr>
            <td style="padding:4px 0;color:#64748B;">Email</td>
            <td style="padding:4px 0;color:#0F172A;">{{ $applicant->email }}</td>
        </tr>
        <tr>
            <td style="padding:4px 0;color:#64748B;">No. WhatsApp</td>
            <td style="padding:4px 0;color:#0F172A;">{{ $applicant->phone_wa ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding:4px 0;color:#64748B;">Peran Diajukan</td>
            <td style="padding:4px 0;font-weight:700;color:#1B3A6B;">{{ \App\Enums\Role::tryFrom((string) $applicant->requested_role)?->label() ?? $applicant->requested_role }}</td>
        </tr>
    </table>
    <div style="text-align:center;">
        <a href="{{ $adminUrl }}" style="display:inline-block;background-color:#1B3A6B;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:10px;border-bottom:3px solid #C9A84C;">
            Tinjau Permintaan Pendaftaran
        </a>
    </div>
@endcomponent

