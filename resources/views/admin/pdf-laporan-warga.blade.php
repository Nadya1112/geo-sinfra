<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Warga - GEO-SINFRA</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm 20mm 20mm 20mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Times New Roman, serif; color: #111; font-size: 12px; line-height: 1.4; }

        /* ── KOP DINAS ── */
        .kop-wrapper { border-bottom: 4px solid #1a1a1a; padding-bottom: 8px; margin-bottom: 4px; }
        .kop-inner { display: table; width: 100%; }
        .kop-logo-kiri, .kop-logo-kanan { display: table-cell; width: 80px; vertical-align: middle; text-align: center; }
        .kop-teks { display: table-cell; vertical-align: middle; text-align: center; padding: 0 8px; }
        .kop-logo-kiri img, .kop-logo-kanan img { max-width: 70px; max-height: 70px; }
        .kop-teks .nama-dinas { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; }
        .kop-teks .alamat, .kop-teks .kontak { font-size: 10px; color: #444; margin-top: 2px; }
        .garis-bawah-kop { border-top: 1px solid #1a1a1a; margin-top: 6px; }

        /* ── JUDUL LAPORAN ── */
        .judul-laporan { text-align: center; margin: 16px 0 12px; padding-bottom: 10px; }
        .judul-laporan h2 { font-size: 16px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .judul-laporan p { font-size: 11px; color: #555; margin-top: 3px; }

        /* ── TABEL DATA ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #f3f4f6; color: #111; font-weight: bold; padding: 8px; border: 1px solid #d1d5db; text-align: left; font-size: 11px; text-transform: uppercase; }
        table td { padding: 6px 8px; border: 1px solid #d1d5db; vertical-align: top; font-size: 11px; color: #333; }
        
        /* ── TANDA TANGAN ── */
        .ttd-wrapper { page-break-inside: avoid; margin-top: 30px; width: 100%; }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td { border: none !important; background: none !important; padding: 0 !important; vertical-align: top; font-size: 11px; color: #111; }
        .ttd-kota-tgl { margin-bottom: 6px; }
        .ttd-jabatan { font-weight: bold; margin-bottom: 4px; }
        .ttd-ruang { height: 60px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-nip { font-size: 10px; color: #444; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
<style>
    
    
</style>
</head>
<body>

    @php
        $logoKiriPath = public_path('logo_dinas.jpeg');
        $logoKiriB64 = file_exists($logoKiriPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoKiriPath)) : '';

        $logoKananPath = public_path('logo_geo-sinfra.png');
        $logoKananB64 = file_exists($logoKananPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoKananPath)) : '';
    @endphp

    <div class="kop-wrapper">
        <div class="kop-inner">
            <div class="kop-logo-kiri">@if($logoKiriB64)<img src="{{ $logoKiriB64 }}" alt="Logo Dinas">@endif</div>
            <div class="kop-teks">
                <div class="nama-dinas">Dinas Perumahan Rakyat dan Kawasan Permukiman</div>
                <div class="nama-dinas">Kota Banjarmasin</div>
                <div class="alamat">Jl. R.E. Martadinata No. 1 Blok B Lt. 2, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70111</div>
                <div class="kontak">Telp: (0511) 3365592 &nbsp;|&nbsp; Email: ampihkumuh@gmail.com</div>
            </div>
            <div class="kop-logo-kanan"></div>
        </div>
        <div class="garis-bawah-kop"></div>
    </div>

    <div class="judul-laporan">
        <h2>Rekapitulasi Laporan Pengaduan Masyarakat (GEO-SINFRA)</h2>
        <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 12%;">Tgl Lapor</th>
                <th style="width: 15%;">Pelapor & Kontak</th>
                <th style="width: 30%;">Deskripsi Laporan</th>
                <th style="width: 15%;">Lokasi (Koordinat)</th>
                <th style="width: 15%;">Analisis AI</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanWarga as $index => $laporan)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y') }}<br>{{ \Carbon\Carbon::parse($laporan->created_at)->format('H:i') }}</td>
                    <td><strong>{{ $laporan->nama_pelapor }}</strong><br>{{ $laporan->no_hp }}</td>
                    <td>{{ $laporan->deskripsi }}</td>
                    <td>{{ $laporan->latitude }}<br>{{ $laporan->longitude }}</td>
                    <td>
                        @if($laporan->label_ai)
                            @php
                                $badgeClass = match($laporan->label_ai) {
                                    'Rusak Berat'  => 'background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5;',
                                    'Rusak Sedang' => 'background-color: #fff7ed; color: #c2410c; border-color: #fdba74;',
                                    default        => 'background-color: #ecfdf5; color: #047857; border-color: #6ee7b7;'
                                };
                            @endphp
                            <span style="display: inline-block; padding: 3px 8px; font-size: 10px; font-weight: bold; text-transform: uppercase; border-radius: 3px; border: 1px solid; {{ $badgeClass }}">{{ strtoupper($laporan->label_ai) }}</span><br>
                            <span style="font-size: 9px; color: #555; margin-top: 3px; display: inline-block;">Skor: {{ $laporan->skor_ai ? round($laporan->skor_ai * 100) . '%' : '-' }}</span>
                        @else
                            <span style="color:#999; font-style:italic;">-</span>
                        @endif
                    </td>
                    <td><strong>{{ strtoupper($laporan->status) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; font-style: italic; color: #777;">Tidak ada data laporan masyarakat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td style="width:60%;"></td>
                <td style="width:40%; text-align:center;">
                    <div class="ttd-kota-tgl">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="ttd-jabatan">Koordinator Tim Teknis</div>
                    <div class="ttd-ruang"></div>
                    <div class="ttd-nama">HIZBULWATHONI, S.T.</div>
                    <div class="ttd-nip">NIP. 19760814 200604 1 008</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak melalui GEO-SINFRA &nbsp;|&nbsp; {{ now()->translatedFormat('d F Y, H:i') }} WITA
        &nbsp;|&nbsp; Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin
    </div>

</body>
</html>
