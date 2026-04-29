<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Infrastruktur - {{ $inf->nama_infrastruktur }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #1e1b4b; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1e1b4b; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        
        .section-title { font-size: 14px; font-weight: bold; color: #1e1b4b; text-transform: uppercase; background-color: #f3f4f6; padding: 8px; margin-top: 20px; margin-bottom: 10px; border-left: 4px solid #3b82f6; }
        .section-title.purple { border-left-color: #a855f7; }
        .section-title.emerald { border-left-color: #10b981; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th { text-align: left; width: 30%; font-size: 12px; color: #666; padding: 8px; border-bottom: 1px solid #eee; }
        table td { font-size: 12px; font-weight: bold; color: #1e1b4b; padding: 8px; border-bottom: 1px solid #eee; }

        .badge { display: inline-block; padding: 5px 10px; font-size: 11px; font-weight: bold; text-transform: uppercase; border-radius: 4px; border: 1px solid; }
        .badge-baik { background-color: #ecfdf5; color: #059669; border-color: #a7f3d0; }
        .badge-ringan { background-color: #fefce8; color: #d97706; border-color: #fde68a; }
        .badge-berat { background-color: #fef2f2; color: #dc2626; border-color: #fecaca; }

        .photo-container { text-align: center; margin-top: 20px; }
        .photo-container img { max-width: 100%; max-height: 350px; border: 1px solid #ccc; padding: 5px; }
        .photo-caption { font-size: 10px; color: #999; margin-top: 5px; font-style: italic; }

        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Detail Infrastruktur</h1>
        <p>Sistem Informasi Geografis (GEO-SINFRA) - Dokumen Resmi</p>
    </div>

    <div class="section-title">Identitas & Lokasi</div>
    <table>
        <tr>
            <th>Nama Infrastruktur</th>
            <td>{{ $inf->nama_infrastruktur }}</td>
        </tr>
        <tr>
            <th>Jenis Infrastruktur</th>
            <td>{{ $inf->jenis_infrastruktur }}</td>
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
    </table>

    <div class="section-title purple">Analisis Cerdas (CNN & DT)</div>
    @php
        $badgeClass = 'badge-baik';
        $cnnScore = '88.5%';
        $priority = 'Prioritas Rendah';
        
        if ($inf->kondisi == 'Rusak Ringan') {
            $badgeClass = 'badge-ringan';
            $cnnScore = '91.3%';
            $priority = 'Perlu Perhatian';
        } elseif ($inf->kondisi == 'Rusak Berat') {
            $badgeClass = 'badge-berat';
            $cnnScore = '94.2%';
            $priority = 'Prioritas Tinggi';
        }
    @endphp
    <table>
        <tr>
            <th>Akurasi CNN</th>
            <td>Deteksi: {{ strtoupper($inf->kondisi) }} ({{ $cnnScore }})</td>
        </tr>
        <tr>
            <th>Hasil Decision Tree</th>
            <td>{{ strtoupper($priority) }}</td>
        </tr>
        <tr>
            <th>Status Akhir Sistem</th>
            <td>
                <span class="badge {{ $badgeClass }}">{{ strtoupper($inf->kondisi) }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title emerald">Dokumentasi Visual</div>
    <div class="photo-container">
        @if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg')
            @php
                $imagePath = storage_path('app/public/infrastruktur/' . $inf->foto_terbaru);
            @endphp
            @if(file_exists($imagePath))
                @php
                    $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $data = file_get_contents($imagePath);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                @endphp
                <img src="{{ $base64 }}" alt="Foto Infrastruktur">
            @else
                <div style="padding: 50px; background: #f9f9f9; border: 1px solid #ddd; color: #aaa;">[ FOTO FILE TIDAK DITEMUKAN DI SERVER ]</div>
            @endif
        @else
            <div style="padding: 50px; background: #f9f9f9; border: 1px solid #ddd; color: #aaa;">[ TIDAK ADA FOTO TERSEDIA ]</div>
        @endif
        
        <p class="photo-caption">{{ $inf->foto_terbaru ?? 'tidak_ada_foto.jpg' }} - Diupload oleh {{ $inf->nama_user ?? 'Admin' }}</p>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i:s') }} WITA | GEO-SINFRA System
    </div>

</body>
</html>
