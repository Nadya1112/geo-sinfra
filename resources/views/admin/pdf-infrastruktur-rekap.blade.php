<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data Infrastruktur - GEO-SINFRA</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 4cm 3cm 3cm 4cm; /* Standar pemerintahan */
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #111; font-size: 10px; line-height: 1.4; }

        /* ── HEADER MINIMALIS ── */
        .header-line { border-top: 2px solid #1a1a1a; margin-bottom: 12px; }

        /* ── JUDUL LAPORAN ── */
        .judul-laporan { text-align: center; margin: 14px 0 10px; }
        .judul-laporan h2 { font-size: 13px; font-weight: bold; text-transform: uppercase; text-decoration: underline; letter-spacing: 0.5px; }
        .judul-laporan p { font-size: 9px; color: #555; margin-top: 3px; }

        /* ── TABEL DATA ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table thead tr { background-color: #1e1b4b; }
        table th {
            color: #fff;
            font-weight: bold;
            padding: 6px 5px;
            border: 1px solid #3730a3;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }
        table td {
            padding: 5px 5px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            font-size: 9px;
            color: #222;
        }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }

        /* ── BADGE KONDISI ── */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 2px;
            border: 1px solid;
        }
        .badge-baik   { background-color: #ecfdf5; color: #047857; border-color: #6ee7b7; }
        .badge-ringan { background-color: #fefce8; color: #a16207; border-color: #fde047; }
        .badge-sedang { background-color: #fff7ed; color: #c2410c; border-color: #fdba74; }
        .badge-berat  { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

        /* ── STATISTIK ── */
        .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stat-table td { padding: 3px 8px; border: none; font-size: 9px; }

        /* ── TANDA TANGAN ── */
        .ttd-wrapper { page-break-inside: avoid; margin-top: 24px; width: 100%; }
        .ttd-table { width: 100%; border-collapse: collapse; }
        .ttd-table td { border: none !important; padding: 0 !important; vertical-align: top; font-size: 10px; color: #111; }
        .ttd-kota-tgl { margin-bottom: 5px; }
        .ttd-jabatan  { font-weight: bold; margin-bottom: 4px; }
        .ttd-ruang    { height: 55px; }
        .ttd-nama     { font-weight: bold; text-decoration: underline; }
        .ttd-nip      { font-size: 9px; color: #444; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: -15px;
            left: 0; right: 0;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- HEADER MINIMALIS                            --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="header-line"></div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- JUDUL LAPORAN                               --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="judul-laporan">
        <h2>Rekapitulasi Data Infrastruktur Permukiman</h2>
        <p>Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</p>
        <p>Tanggal Cetak: {{ now()->translatedFormat('d F Y') }} &nbsp;|&nbsp; Total Data: {{ count($infrastrukturs) }} Aset</p>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TABEL DATA                                  --}}
    {{-- ═══════════════════════════════════════════ --}}
    <table>
        <thead>
            <tr>
                <th style="width:3%; text-align:center;">No</th>
                <th style="width:5%;">ID</th>
                <th style="width:18%;">Nama Infrastruktur</th>
                <th style="width:8%;">Jenis</th>
                <th style="width:12%;">Kecamatan</th>
                <th style="width:12%;">Kelurahan</th>
                <th style="width:7%;">Dimensi (m)</th>
                <th style="width:8%;">Material</th>
                <th style="width:8%;">Skor DT</th>
                <th style="width:12%;">Analisis CNN</th>
                <th style="width:7%; text-align:center;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($infrastrukturs as $index => $inf)
            @php
                $badgeClass = match($inf->label_prioritas ?? '') {
                    'Kondisi Rusak Berat'  => 'badge-berat',
                    'Kondisi Rusak Sedang' => 'badge-sedang',
                    'Kondisi Rusak Ringan' => 'badge-ringan',
                    default                => 'badge-baik'
                };
            @endphp
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>INF-{{ $inf->id_infrastruktur }}</td>
                <td><strong>{{ $inf->nama_objek ?? '-' }}</strong></td>
                <td>{{ ucfirst($inf->jenis ?? '-') }}</td>
                <td>{{ $inf->nama_kecamatan ?? '-' }}</td>
                <td>{{ $inf->nama_kelurahan ?? '-' }}</td>
                <td>{{ $inf->panjang ?? '-' }}m &times; {{ $inf->lebar ?? '-' }}m</td>
                <td>{{ $inf->material_eksisting ?? '-' }}</td>
                <td>{{ $inf->skor_dt ?? '0' }}/100</td>
                <td>{{ $inf->label_cnn ?? 'Belum Dianalisis' }}</td>
                <td style="text-align:center;">
                    <span class="badge {{ $badgeClass }}">
                        {{ strtoupper(str_replace('Kondisi ', '', $inf->label_prioritas ?? 'Belum')) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align:center; padding: 20px; color: #9ca3af;">
                    Tidak ada data infrastruktur.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TANDA TANGAN                                --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                <td style="width:50%;"></td>
                @php
                    $timTeknis = \App\Models\User::where('role', 'tim_teknis')->first();
                @endphp
                <td style="width:50%; text-align:center;">
                    <div class="ttd-kota-tgl">Banjarmasin, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="ttd-jabatan">Koordinator Tim Teknis</div>
                    <div class="ttd-ruang"></div>
                    <div class="ttd-nama">{{ strtoupper($timTeknis->name ?? 'HIZBULWATHONI, S.T.') }}</div>
                    <div class="ttd-nip">NIP. {{ $timTeknis->nip ?? '19760814 200604 1 008' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- FOOTER                                      --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div class="footer">
        Dicetak melalui GEO-SINFRA &nbsp;|&nbsp; {{ now()->translatedFormat('d F Y, H:i') }} WITA
        &nbsp;|&nbsp; Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin
    </div>

</body>
</html>
