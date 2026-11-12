@component('emails.layout', ['title' => $approved ? 'Akun Disetujui' : 'Pendaftaran Ditolak'])
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;color:#1B3A6B;">
        {{ $approved ? 'Akun Anda Telah Disetujui' : 'Pendaftaran Akun Ditolak' }}
    </h2>
    <p style="margin:0 0 14px;">
        Halo <strong>{{ $user->name }}</strong>,
    </p>

    @if($approved)
        <p style="margin:0 0 14px;">
            Pendaftaran akun Anda di portal persuratan <strong>ARCHIVA SDN Jatipulo 07</strong> telah <strong>disetujui</strong> oleh Administrator.
        </p>
        <div style="background-color:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#065F46;">
            Peran aktif Anda: <strong>{{ $user->role instanceof \App\Enums\Role ? $user->role->label() : $user->role }}</strong>
        </div>
        <div style="text-align:center;">
            <a href="{{ $loginUrl }}" style="display:inline-block;background-color:#1B3A6B;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:10px;border-bottom:3px solid #C9A84C;">
                Masuk ke Portal ARCHIVA
            </a>
        </div>
    @else
        <p style="margin:0 0 14px;">
            Mohon maaf, pendaftaran akun Anda di <strong>ARCHIVA SDN Jatipulo 07</strong> belum dapat disetujui oleh Administrator.
        </p>
        @if($reason)
            <div style="background-color:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#991B1B;">
                <strong>Catatan Administrator:</strong><br>
                {{ $reason }}
            </div>
        @endif
        <p style="margin:0;font-size:13px;color:#475569;">
            Silakan hubungi Tata Usaha atau Administrator sekolah jika memerlukan bantuan lebih lanjut.
        </p>
    @endif
@endcomponent

