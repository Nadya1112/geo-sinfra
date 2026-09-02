<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Infrastruktur - {{ $inf->nama_objek ?? $inf->nama_infrastruktur ?? 'Tanpa Nama' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm 1.5cm 1.5cm 1.5cm; /* Margin diminimalkan agar muat 1 halaman */
        }
        div, span, h1, h2, h3, p, table, tbody, tr, th, td { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 12px; line-height: 1.5; }

        /* ── JUDUL LAPORAN ── */
        .judul-laporan {
            text-align: center;
            margin: 0 0 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 6px;
        }
        .judul-laporan h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
        }
        .judul-laporan p {
            font-size: 11px;
            color: #555;
            margin-top: 3px;
        }

        /* ── SECTION ── */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e1b4b;
            text-transform: uppercase;
            background-color: #f3f4f6;
            padding: 3px 6px;
            margin-top: 6px;
            margin-bottom: 4px;
            border-left: 4px solid #3b82f6;
        }
        .section-title.purple { border-left-color: #7c3aed; }
        .section-title.emerald { border-left-color: #059669; }

        /* ── TABEL DATA ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        table th {
            text-align: left;
            width: 32%;
            font-size: 11px;
            font-weight: normal;
            color: #555;
            padding: 3px 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table td {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            padding: 3px 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
            border: 1px solid;
        }
        .badge-baik   { background-color: #ecfdf5; color: #047857; border-color: #6ee7b7; }
        .badge-ringan { background-color: #fefce8; color: #a16207; border-color: #fde047; }
        .badge-sedang { background-color: #fff7ed; color: #c2410c; border-color: #fdba74; }
        .badge-berat  { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

        /* ── FOTO ── */
        .photo-container { text-align: center; margin-top: 6px; }
        .photo-container img {
            max-width: 100%;
            max-height: 180px; /* Dikompres ekstrim agar menghemat ruang */
            border: 1px solid #d1d5db;
            padding: 3px;
        }
        .photo-caption { font-size: 9px; color: #9ca3af; margin-top: 2px; font-style: italic; }

        /* ── TANDA TANGAN ── */
        .ttd-wrapper {
            page-break-inside: avoid;
            margin-top: 16px;
            width: 100%;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }
        .ttd-table td {
            border: none !important;
            background: none !important;
            padding: 0 !important;
            vertical-align: top;
            font-size: 11px;
            font-weight: normal;
            color: #111;
        }
        .ttd-kota-tgl { margin-bottom: 6px; }
        .ttd-jabatan  { font-weight: bold; margin-bottom: 4px; }
        .ttd-ruang    { height: 40px; }
        .ttd-nama     { font-weight: bold; text-decoration: underline; }
        .ttd-nip      { font-size: 10px; color: #444; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: 0px; /* Dinaikkan sesuai permintaan */
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
</head>
<body>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- JUDUL LAPORAN                               --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="judul-laporan">
        <h2>Laporan Data Infrastruktur Permukiman</h2>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- 1. IDENTITAS & LOKASI                       --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="section-title">1. Identitas &amp; Lokasi</div>
    <table>
        <tr>
            <th>Nama Infrastruktur</th>
            <td>{{ $inf->nama_objek ?? $inf->nama_infrastruktur ?? 'Tanpa Nama' }}</td>
        </tr>
        <tr>
            <th>Jenis Infrastruktur</th>
            <td>{{ ucfirst($inf->jenis) }}</td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td>{{ $inf->nama_kecamatan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kelurahan</th>
            <td>{{ $inf->nama_kelurahan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Koordinat (Lat, Lng)</th>
            <td>{{ $inf->latitude }}, {{ $inf->longitude }}</td>
        </tr>
        <tr>
            <th>Tanggal Survey</th>
            <td>{{ $inf->tgl_survey ? \Carbon\Carbon::parse($inf->tgl_survey)->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Surveyor</th>
            <td>{{ $inf->nama_user ?? '-' }}</td>
        </tr>
        <tr>
            <th>Dimensi</th>
            <td>{{ $inf->panjang ?? '-' }} m &times; {{ $inf->lebar ?? '-' }} m</td>
        </tr>
        <tr>
            <th>Material Eksisting</th>
            <td>{{ $inf->material_eksisting ?? '-' }}</td>
        </tr>
    </table>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- 2. HASIL ANALISIS AI                        --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="section-title purple">2. Hasil Analisis Hybrid AI (Visual CNN &amp; Decision Tree)</div>
    @php
        $badgeClass = match($inf->label_prioritas) {
            'Kondisi Rusak Berat'  => 'badge-berat',
            'Kondisi Rusak Sedang' => 'badge-sedang',
            'Kondisi Rusak Ringan' => 'badge-ringan',
            default                => 'badge-baik'
        };
    @endphp
    <table>
        <tr>
            <th>Analisis Visual (CNN)</th>
            <td>{{ $inf->label_cnn ?? 'Belum Dianalisis' }}
                @if($inf->skor_cnn) ({{ round($inf->skor_cnn * 100) }}% keyakinan) @endif
            </td>
        </tr>
        <tr>
            <th>Structural Logic (Decision Tree)</th>
            <td>Skor: {{ $inf->skor_dt ?? 0 }} / 100</td>
        </tr>
        <tr>
            <th>Status Prioritas Akhir</th>
            <td><span class="badge {{ $badgeClass }}">{{ strtoupper($inf->label_prioritas ?? 'Belum Dianalisis') }}</span></td>
        </tr>
        <tr>
            <th>Rekomendasi Sistem</th>
            <td style="font-style:italic; color:#374151;">"{{ $inf->rekomendasi ?? 'Menunggu hasil analisis...' }}"</td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td>{{ $inf->status_verifikasi ?? 'Pending' }}</td>
        </tr>
    </table>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- 3. DOKUMENTASI VISUAL                       --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="section-title emerald">3. Dokumentasi Visual</div>
    <div class="photo-container">
        @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
            @php
                // Normalisasi path: bersihkan backslash Windows & pastikan prefix folder benar
                $rawPath   = str_replace('\\', '/', $inf->foto_terbaru);
                $cleanPath = str_contains($rawPath, 'infrastruktur/') ? $rawPath : 'infrastruktur/' . $rawPath;

                // Absolute path ke file di storage
                $imagePath = storage_path('app/public/' . $cleanPath);

                // Normalisasi extension ke lowercase agar MIME type valid
                $ext     = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                // 'jpg' HARUS dikonversi ke 'jpeg' untuk data URI yang benar di DomPDF
                $mimeExt = ($ext === 'jpg') ? 'jpeg' : $ext;
            @endphp

            @if(file_exists($imagePath))
                @php
                    $data   = file_get_contents($imagePath);
                    $base64 = 'data:image/' . $mimeExt . ';base64,' . base64_encode($data);
                @endphp
                <img src="{{ $base64 }}" alt="Foto Infrastruktur">
            @else
                <div style="padding:30px; background:#fef9ec; border:1px dashed #f59e0b; color:#92400e; font-size:10px; text-align:center;">
                    <strong>[ FOTO TIDAK DITEMUKAN ]</strong><br>
                    <span style="color:#aaa; font-size:9px;">{{ $imagePath }}</span>
                </div>
            @endif
        @else
            <div style="padding:40px; background:#f9fafb; border:1px dashed #d1d5db; color:#9ca3af; text-align:center; font-size:11px;">
                [ TIDAK ADA FOTO TERSEDIA ]
            </div>
        @endif
        <p class="photo-caption">
            Foto: {{ basename($inf->foto_terbaru ?? 'tidak_ada_foto.jpg') }}
            &nbsp;&mdash;&nbsp; Diupload oleh: {{ $inf->nama_user ?? 'Admin' }}
        </p>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TANDA TANGAN                                --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                {{-- Kolom kiri: kosong --}}
                <td style="width:50%;"></td>

                @php
                    $timTeknis = \App\Models\User::where('role', 'tim_teknis')->first();
                @endphp
                {{-- Kolom kanan: TTD --}}
                <td style="width:50%; text-align:center;">
                    <div class="ttd-kota-tgl">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="ttd-jabatan">Koordinator Tim Teknis</div>
                    <div class="ttd-ruang"></div>
                    <div class="ttd-nama" style="text-decoration: underline;">{{ strtoupper(optional($timTeknis)->name ?? 'HIZBULWATHONI, S.T.') }}</div>
                    <div class="ttd-nip">NIP. {{ optional($timTeknis)->nip ?? '19760814 200604 1 008' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- FOOTER                                      --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="footer">
        Dicetak melalui GEO-SINFRA &nbsp;|&nbsp; {{ now()->translatedFormat('d F Y, H:i') }} WITA
    </div>

</body>
</html>
