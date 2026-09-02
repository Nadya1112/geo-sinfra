<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Warga - GEO-SINFRA</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm 1.5cm 1.5cm 1.5cm; /* Margin diminimalkan */
        }
        div, span, h1, h2, h3, p, table, tbody, tr, th, td { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 11px; line-height: 1.4; }


        /* ── JUDUL LAPORAN ── */
        .judul-laporan { text-align: center; margin: 16px 0 12px; padding-bottom: 10px; }
        .judul-laporan h2 { font-size: 16px; font-weight: bold; text-transform: uppercase; text-decoration: underline; }
        .judul-laporan p { font-size: 11px; color: #555; margin-top: 3px; }

        /* ── TABEL DATA ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #f3f4f6; color: #111; font-weight: bold; padding: 8px; border: 1px solid #d1d5db; text-align: left; font-size: 11px; text-transform: uppercase; }
        table td { padding: 6px 8px; border: 1px solid #d1d5db; vertical-align: top; font-size: 11px; color: #333; }
        
        /* ── TANDA TANGAN ── */
        .ttd-wrapper { page-break-inside: avoid; margin-top: 60px; width: 100%; }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td { border: none !important; background: none !important; padding: 0 !important; vertical-align: top; font-size: 11px; color: #111; }
        .ttd-kota-tgl { margin-bottom: 6px; }
        .ttd-jabatan { font-weight: bold; margin-bottom: 4px; }
        .ttd-ruang { height: 40px; }
        .ttd-nama { font-weight: bold; text-decoration: underline; }
        .ttd-nip { font-size: 10px; color: #444; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer { position: fixed; bottom: 0px; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body>



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
                                    'Kondisi Rusak Berat'  => 'background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5;',
                                    'Kondisi Rusak Sedang' => 'background-color: #fff7ed; color: #c2410c; border-color: #fdba74;',
                                    'Kondisi Rusak Ringan' => 'background-color: #fefce8; color: #a16207; border-color: #fde047;',
                                    default                => 'background-color: #ecfdf5; color: #047857; border-color: #6ee7b7;'
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
                @php
                    $timTeknis = \App\Models\User::where('role', 'tim_teknis')->first();
                @endphp
                <td style="width:40%; text-align:center;">
                    <div class="ttd-kota-tgl">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="ttd-jabatan">Koordinator Tim Teknis</div>
                    <div class="ttd-ruang"></div>
                    <div class="ttd-nama" style="text-decoration: underline;">{{ strtoupper(optional($timTeknis)->name ?? 'HIZBULWATHONI, S.T.') }}</div>
                    <div class="ttd-nip">NIP. {{ optional($timTeknis)->nip ?? '19760814 200604 1 008' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak melalui GEO-SINFRA &nbsp;|&nbsp; {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>
