<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lembar Disposisi — {{ $suratMasuk->nomor_agenda }}</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .paper {
            background-color: #fff;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm 20mm 25mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 75px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-text h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text h2 {
            margin: 2px 0;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text h1 {
            margin: 2px 0;
            font-size: 17pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-text p {
            margin: 2px 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }

        .kop-line-double {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-bottom: 16px;
        }

        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 12px 0 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10.5pt;
        }

        .content-table th, .content-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }

        .content-table th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        .disp-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
            min-height: 120px;
            font-size: 11pt;
            line-height: 1.5;
        }

        .sig-section {
            margin-top: 30px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            font-size: 11pt;
        }

        .sig-box {
            width: 250px;
            text-align: center;
        }

        .sig-space {
            height: 70px;
        }

        .print-bar {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #1B3A6B;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .btn-close {
            background: #fff;
            color: #333;
            border: 1px solid #ccc;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .paper {
                box-shadow: none;
                max-width: 100%;
                min-height: auto;
                padding: 0;
                margin: 0;
            }

            .print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak Browser -->
    <div class="print-bar">
        <button type="button" class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button type="button" class="btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="paper">
        <!-- KOP SURAT DINAS RESMI -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="{{ asset('images/logo-sdn-jatipulo07.png') }}" alt="Logo SDN Jatipulo 07">
                </td>
                <td class="kop-text">
                    <h3>Pemerintah Provinsi Daerah Khusus Ibukota Jakarta</h3>
                    <h2>Dinas Pendidikan</h2>
                    <h1>SDN JATIPULO 07 JAKARTA</h1>
                    <p>Jl. Semangka III No. 1, RT.003/RW.07, Jatipulo, Kec. Palmerah, Kota Jakarta Barat, DKI Jakarta 11430</p>
                </td>
            </tr>
        </table>

        <div class="kop-line-double"></div>

        <div class="doc-title">LEMBAR DISPOSISI KEPALA SEKOLAH</div>

        <!-- Tabel Informasi Surat Masuk -->
        <table class="content-table">
            <tr>
                <td style="width: 20%; font-weight: bold;">Nomor Agenda</td>
                <td style="width: 35%; font-family: monospace; font-weight: bold;">{{ $suratMasuk->nomor_agenda }}</td>
                <td style="width: 20%; font-weight: bold;">Tingkat Sifat</td>
                <td style="width: 25%; font-weight: bold;">{{ strtoupper($suratMasuk->sifat) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tanggal Diterima</td>
                <td>{{ $suratMasuk->tanggal_diterima->isoFormat('D MMMM Y') }}</td>
                <td style="font-weight: bold;">Kategori Surat</td>
                <td>{{ $suratMasuk->kategori }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Nomor Surat Asli</td>
                <td colspan="3" style="font-family: monospace;">{{ $suratMasuk->nomor_surat_asli ?? '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Tanggal Surat</td>
                <td colspan="3">{{ $suratMasuk->tanggal_surat->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Asal / Pengirim</td>
                <td colspan="3" style="font-weight: bold;">{{ $suratMasuk->pengirim }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Perihal</td>
                <td colspan="3">{{ $suratMasuk->perihal }}</td>
            </tr>
        </table>

        <!-- Kolom Instruksi Disposisi Pimpinan -->
        <table class="content-table" style="margin-top: 10px;">
            <tr>
                <th style="width: 50%;">DITERUSKAN KEPADA</th>
                <th style="width: 50%;">PETUNJUK / INSTRUKSI PIMPINAN</th>
            </tr>
            @forelse($suratMasuk->disposisi as $d)
                <tr>
                    <td>
                        <strong>{{ $d->toUser->name }}</strong><br>
                        <span style="font-size: 9.5pt; color: #444;">
                            Jabatan: {{ strtoupper(str_replace('_', ' ', $d->toUser->role->value ?? $d->toUser->role)) }}
                            {{ $d->toUser->unit ? ' (' . $d->toUser->unit . ')' : '' }}
                        </span><br>
                        @if($d->batas_waktu)
                            <span style="font-size: 9pt; color: #a00;">
                                Batas Waktu: {{ $d->batas_waktu->isoFormat('D MMMM Y') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 10.5pt; line-height: 1.4;">
                            {{ $d->instruksi }}
                        </div>
                        @if($d->catatan_tindak_lanjut)
                            <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed #ccc; font-size: 9pt; color: #005500;">
                                <strong>Catatan Tindak Lanjut:</strong> {{ $d->catatan_tindak_lanjut }}
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #888; padding: 20px;">
                        <em>(Belum ada instruksi disposisi dari pimpinan)</em>
                    </td>
                </tr>
            @endforelse
        </table>

        <!-- Kolom Paraf dan Tanda Tangan -->
        <div class="sig-section">
            <div class="sig-box">
                <p style="margin: 0;">Jakarta, {{ now()->isoFormat('D MMMM Y') }}</p>
                <p style="margin: 3px 0 0; font-weight: bold;">Kepala SDN Jatipulo 07 Jakarta,</p>
                <div class="sig-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">Dra. Siti Nurhaliza, M.Pd.</p>
                <p style="margin: 2px 0 0; font-size: 9.5pt;">NIP. 196805121993032004</p>
            </div>
        </div>
    </div>

</body>
</html>

