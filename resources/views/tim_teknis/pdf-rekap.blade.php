<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Validasi Infrastruktur SINFRA</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 30mm 30mm 30mm 40mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .data-table td.center {
            text-align: center;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-validated { background-color: #d1fae5; color: #065f46; }
        .status-rejected { background-color: #ffe4e6; color: #9f1239; }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            page-break-inside: avoid;
        }
        .signature {
            display: inline-block;
            text-align: center;
            width: 350px;
        }
        .signature-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>REKAPITULASI VALIDASI USULAN INFRASTRUKTUR</h1>
        <p>TIM TEKNIS (GEO-SINFRA)</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Filter Status</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ $request->status && $request->status != 'All' ? ($request->status == 'Validated' ? 'Disetujui' : ($request->status == 'Rejected' ? 'Ditolak' : 'Menunggu')) : 'Semua Status' }}</td>
            <td width="15%"><strong>Dicetak Pada</strong></td>
            <td width="2%">:</td>
            <td width="33%">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Wilayah</strong></td>
            <td>:</td>
            <td>{{ $kecamatanName }}</td>
            <td><strong>Periode</strong></td>
            <td>:</td>
            <td>
                {{ $request->start ? \Carbon\Carbon::parse($request->start)->format('d/m/Y') : '-' }} 
                s.d 
                {{ $request->end ? \Carbon\Carbon::parse($request->end)->format('d/m/Y') : '-' }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Nama Infrastruktur</th>
                <th width="12%">Wilayah</th>
                <th width="10%">Surveyor</th>
                <th width="15%">Kondisi Lapangan</th>
                <th width="15%">Prioritas (AI/Teknis)</th>
                <th width="20%">Status & Pengerjaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allUsulan as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->nama_objek }}</strong><br>
                    <span style="font-size: 8pt; color: #666;">{{ strtoupper($item->jenis) }}</span>
                </td>
                <td>
                    {{ $item->kelurahan->nama_kelurahan ?? '-' }}<br>
                    <span style="font-size: 8pt; color: #666;">Kec. {{ $item->kelurahan->kecamatan->nama_kecamatan ?? '-' }}</span>
                </td>
                <td>{{ $item->user->name ?? 'Anonim' }}</td>
                <td>
                    {{ $item->kondisi }}
                </td>
                <td>
                    @if($item->rekomendasi_manual)
                        <div style="margin-bottom: 4px;"><strong>Manual:</strong> {{ $item->rekomendasi_manual }}</div>
                    @else
                        <div style="margin-bottom: 4px;"><strong>AI:</strong> {{ $item->analisis->label_prioritas ?? 'Belum' }} (Skor: {{ number_format($item->analisis->skor_dt ?? 0, 1) }}%)</div>
                    @endif
                </td>
                <td>
                    @php
                        $statusClass = match($item->status_validasi) {
                            'Validated' => 'status-validated',
                            'Rejected' => 'status-rejected',
                            default => 'status-pending'
                        };
                        $statusText = match($item->status_validasi) {
                            'Validated' => 'DITERIMA',
                            'Rejected' => 'DITOLAK',
                            default => 'MENUNGGU'
                        };
                    @endphp
                    <div style="margin-bottom: 5px;">
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </div>
                    
                    @if($item->status_validasi == 'Validated')
                        <div style="font-size: 8pt; margin-top: 5px;">
                            <strong>Progress:</strong> {{ $item->status_perbaikan ?? 'Menunggu' }}<br>
                            @if($item->pelaksana_perbaikan)
                                <strong>Vendor:</strong> {{ $item->pelaksana_perbaikan }}<br>
                            @endif
                            @if($item->estimasi_selesai)
                                <strong>Est. Selesai:</strong> {{ \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y') }}
                            @endif
                        </div>
                    @elseif($item->status_validasi == 'Rejected')
                        <div style="font-size: 8pt; color: #9f1239; margin-top: 5px;">
                            <strong>Alasan:</strong> {{ $item->alasan_penolakan ?? '-' }}
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="center">Tidak ada data untuk filter yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        @php
            $timTeknis = \App\Models\User::where('role', 'tim_teknis')->first();
        @endphp
        <div class="signature">
            <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Koordinator Tim Teknis</strong></p>
            <div class="signature-name" style="text-decoration: underline; margin-bottom: 2px;">{{ strtoupper($timTeknis->name ?? 'HIZBULWATHONI, S.T.') }}</div>
            <div style="font-size: 11px;">NIP. {{ $timTeknis->nip ?? '19760814 200604 1 008' }}</div>
        </div>
    </div>

</body>
</html>
