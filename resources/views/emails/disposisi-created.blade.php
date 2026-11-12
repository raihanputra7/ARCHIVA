@component('emails.layout', ['title' => 'Disposisi Surat Masuk Baru'])
    <h2 style="margin:0 0 12px;font-size:18px;font-weight:700;color:#1B3A6B;">
        Disposisi Surat Masuk Baru
    </h2>
    <p style="margin:0 0 14px;">
        Yth. <strong>{{ $recipient->name }}</strong>, Anda menerima instruksi disposisi surat dinas baru di portal ARCHIVA:
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:14px 16px;margin-bottom:20px;font-size:13px;">
        <tr>
            <td style="padding:5px 0;color:#64748B;width:130px;vertical-align:top;">Perihal</td>
            <td style="padding:5px 0;font-weight:700;color:#0F172A;">{{ $surat->perihal }}</td>
        </tr>
        <tr>
            <td style="padding:5px 0;color:#64748B;vertical-align:top;">Nomor Surat</td>
            <td style="padding:5px 0;color:#0F172A;">{{ $surat->nomor_surat_asli ?: $surat->nomor_agenda }}</td>
        </tr>
        <tr>
            <td style="padding:5px 0;color:#64748B;vertical-align:top;">Pengirim</td>
            <td style="padding:5px 0;color:#0F172A;">{{ $surat->pengirim }}</td>
        </tr>
        <tr>
            <td style="padding:5px 0;color:#64748B;vertical-align:top;">Instruksi</td>
            <td style="padding:5px 0;color:#0F172A;">{{ $disposisi->instruksi }}</td>
        </tr>
        @if($disposisi->batas_waktu)
            <tr>
                <td style="padding:5px 0;color:#64748B;vertical-align:top;">Batas Waktu</td>
                <td style="padding:5px 0;font-weight:700;color:#B45309;">{{ $disposisi->batas_waktu->isoFormat('D MMMM Y') }}</td>
            </tr>
        @endif
    </table>

    <div style="text-align:center;">
        <a href="{{ $portalUrl }}" style="display:inline-block;background-color:#1B3A6B;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:10px;border-bottom:3px solid #C9A84C;">
            Buka Surat di Portal ARCHIVA
        </a>
    </div>
@endcomponent

