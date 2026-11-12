<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ARCHIVA SDN Jatipulo 07' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#F4F6FA;font-family:'Segoe UI',Arial,Helvetica,sans-serif;color:#0F172A;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#F4F6FA;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;background-color:#FFFFFF;border:1px solid #E2E8F0;border-radius:16px;overflow:hidden;">
                    <!-- Header Navy & Gold -->
                    <tr>
                        <td style="background-color:#1B3A6B;padding:20px 28px;border-bottom:4px solid #C9A84C;">
                            <div style="font-size:18px;font-weight:800;color:#FFFFFF;letter-spacing:0.5px;">ARCHIVA</div>
                            <div style="font-size:12px;color:#E2E8F0;margin-top:2px;">Portal Persuratan &amp; Arsip Digital — SDN Jatipulo 07 Jakarta</div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:28px;font-size:14px;line-height:1.6;color:#1E293B;">
                            {{ $slot }}
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#F8FAFC;padding:16px 28px;border-top:1px solid #E2E8F0;font-size:11px;color:#64748B;line-height:1.5;">
                            Email ini dikirim otomatis oleh sistem persuratan <strong>ARCHIVA SDN Jatipulo 07</strong>.
                            Mohon tidak membalas langsung ke alamat ini.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

