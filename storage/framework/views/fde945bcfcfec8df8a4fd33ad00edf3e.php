<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Infrastruktur - <?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur ?? 'Tanpa Nama'); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 25mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Times New Roman, serif; color: #111; font-size: 14px; line-height: 1.5; }

        /* ── KOP DINAS ── */
        .kop-wrapper {
            border-bottom: 4px solid #1a1a1a;
            padding-bottom: 8px;
            margin-bottom: 4px;
        }
        .kop-inner {
            display: table;
            width: 100%;
        }
        .kop-logo-kiri {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .kop-logo-kanan {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .kop-teks {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 8px;
        }
        .kop-logo-kiri img,
        .kop-logo-kanan img {
            max-width: 70px;
            max-height: 70px;
        }
        .kop-teks .pemerintah {
            font-size: 11px;
            font-weight: normal;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-teks .nama-dinas {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a1a1a;
        }
        .kop-teks .alamat {
            font-size: 10px;
            color: #444;
            margin-top: 2px;
        }
        .kop-teks .kontak {
            font-size: 10px;
            color: #444;
        }
        .garis-bawah-kop {
            border-top: 1px solid #1a1a1a;
            margin-top: 6px;
        }

        /* ── JUDUL LAPORAN ── */
        .judul-laporan {
            text-align: center;
            margin: 16px 0 12px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
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
            padding: 6px 8px;
            margin-top: 14px;
            margin-bottom: 8px;
            border-left: 4px solid #3b82f6;
        }
        .section-title.purple { border-left-color: #7c3aed; }
        .section-title.emerald { border-left-color: #059669; }

        /* ── TABEL DATA ── */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th {
            text-align: left;
            width: 32%;
            font-size: 11px;
            font-weight: normal;
            color: #555;
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table td {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            padding: 6px 8px;
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
        .badge-sedang { background-color: #fff7ed; color: #c2410c; border-color: #fdba74; }
        .badge-berat  { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }

        /* ── FOTO ── */
        .photo-container { text-align: center; margin-top: 12px; }
        .photo-container img {
            max-width: 100%;
            max-height: 280px;
            border: 1px solid #d1d5db;
            padding: 4px;
        }
        .photo-caption { font-size: 9px; color: #9ca3af; margin-top: 4px; font-style: italic; }

        /* ── TANDA TANGAN ── */
        .ttd-wrapper {
            page-break-inside: avoid;
            margin-top: 28px;
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
        .ttd-ruang    { height: 60px; }
        .ttd-nama     { font-weight: bold; text-decoration: underline; }
        .ttd-nip      { font-size: 10px; color: #444; margin-top: 2px; }

        /* ── FOOTER ── */
        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
    </style>
<style>
    @media (min-width: 768px) { html { font-size: 14px; } }
    @media (max-width: 767px) { html { font-size: 12px; } }
</style>
</head>
<body>

    
    
    
    <?php
        // Logo Dinas (kiri)
        $logoKiriPath = public_path('logo_dinas.jpeg');
        $logoKiriB64  = '';
        if (file_exists($logoKiriPath)) {
            $logoKiriB64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoKiriPath));
        }

        // Logo GEO-SINFRA (kanan)
        $logoKananPath = public_path('logo_geo-sinfra.png');
        $logoKananB64  = '';
        if (file_exists($logoKananPath)) {
            $logoKananB64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoKananPath));
        }
    ?>

    <div class="kop-wrapper">
        <div class="kop-inner">
            
            <div class="kop-logo-kiri">
                <?php if($logoKiriB64): ?>
                    <img src="<?php echo e($logoKiriB64); ?>" alt="Logo Dinas">
                <?php endif; ?>
            </div>

            
            <div class="kop-teks">
                <div class="nama-dinas">Dinas Perumahan Rakyat dan Kawasan Permukiman</div>
                <div class="nama-dinas">Kota Banjarmasin</div>
                <div class="alamat">Jl. R.E. Martadinata No. 1 Blok B Lt. 2, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70111</div>
                <div class="kontak">Telp: (0511) 3365592 &nbsp;|&nbsp; Email: ampihkumuh@gmail.com</div>
            </div>

            
            <div class="kop-logo-kanan">
                
            </div>
        </div>
        <div class="garis-bawah-kop"></div>
    </div>

    
    
    
    <div class="judul-laporan">
        <h2>Laporan Data Infrastruktur Permukiman</h2>
    </div>

    
    
    
    <div class="section-title">1. Identitas &amp; Lokasi</div>
    <table>
        <tr>
            <th>Nama Infrastruktur</th>
            <td><?php echo e($inf->nama_objek ?? $inf->nama_infrastruktur ?? 'Tanpa Nama'); ?></td>
        </tr>
        <tr>
            <th>Jenis Infrastruktur</th>
            <td><?php echo e(ucfirst($inf->jenis)); ?></td>
        </tr>
        <tr>
            <th>Kecamatan</th>
            <td><?php echo e($inf->nama_kecamatan ?? '-'); ?></td>
        </tr>
        <tr>
            <th>Kelurahan</th>
            <td><?php echo e($inf->nama_kelurahan ?? '-'); ?></td>
        </tr>
        <tr>
            <th>Koordinat (Lat, Lng)</th>
            <td><?php echo e($inf->latitude); ?>, <?php echo e($inf->longitude); ?></td>
        </tr>
        <tr>
            <th>Tanggal Survey</th>
            <td><?php echo e($inf->tgl_survey ? \Carbon\Carbon::parse($inf->tgl_survey)->translatedFormat('d F Y') : '-'); ?></td>
        </tr>
        <tr>
            <th>Surveyor</th>
            <td><?php echo e($inf->nama_user ?? '-'); ?></td>
        </tr>
        <tr>
            <th>Dimensi</th>
            <td><?php echo e($inf->panjang ?? '-'); ?> m &times; <?php echo e($inf->lebar ?? '-'); ?> m</td>
        </tr>
        <tr>
            <th>Material Eksisting</th>
            <td><?php echo e($inf->material_eksisting ?? '-'); ?></td>
        </tr>
    </table>

    
    
    
    <div class="section-title purple">2. Hasil Analisis Hybrid AI (Visual CNN &amp; Decision Tree)</div>
    <?php
        $badgeClass = match($inf->label_prioritas) {
            'Rusak Berat'  => 'badge-berat',
            'Rusak Sedang' => 'badge-sedang',
            default        => 'badge-baik'
        };
    ?>
    <table>
        <tr>
            <th>Analisis Visual (CNN)</th>
            <td><?php echo e($inf->label_cnn ?? 'Belum Dianalisis'); ?>

                <?php if($inf->skor_cnn): ?> (<?php echo e(round($inf->skor_cnn * 100)); ?>% keyakinan) <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Structural Logic (Decision Tree)</th>
            <td>Skor: <?php echo e($inf->skor_dt ?? 0); ?> / 100</td>
        </tr>
        <tr>
            <th>Status Prioritas Akhir</th>
            <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e(strtoupper($inf->label_prioritas ?? 'Belum Dianalisis')); ?></span></td>
        </tr>
        <tr>
            <th>Rekomendasi Sistem</th>
            <td style="font-style:italic; color:#374151;">"<?php echo e($inf->rekomendasi ?? 'Menunggu hasil analisis...'); ?>"</td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td><?php echo e($inf->status_verifikasi ?? 'Pending'); ?></td>
        </tr>
    </table>

    
    
    
    <div class="section-title emerald">3. Dokumentasi Visual</div>
    <div class="photo-container">
        <?php if($inf->foto_terbaru && $inf->foto_terbaru != 'default.jpg'): ?>
            <?php
                // Normalisasi path: bersihkan backslash Windows & pastikan prefix folder benar
                $rawPath   = str_replace('\\', '/', $inf->foto_terbaru);
                $cleanPath = str_contains($rawPath, 'infrastruktur/') ? $rawPath : 'infrastruktur/' . $rawPath;

                // Absolute path ke file di storage
                $imagePath = storage_path('app/public/' . $cleanPath);

                // Normalisasi extension ke lowercase agar MIME type valid
                $ext     = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                // 'jpg' HARUS dikonversi ke 'jpeg' untuk data URI yang benar di DomPDF
                $mimeExt = ($ext === 'jpg') ? 'jpeg' : $ext;
            ?>

            <?php if(file_exists($imagePath)): ?>
                <?php
                    $data   = file_get_contents($imagePath);
                    $base64 = 'data:image/' . $mimeExt . ';base64,' . base64_encode($data);
                ?>
                <img src="<?php echo e($base64); ?>" alt="Foto Infrastruktur">
            <?php else: ?>
                <div style="padding:30px; background:#fef9ec; border:1px dashed #f59e0b; color:#92400e; font-size:10px; text-align:center;">
                    <strong>[ FOTO TIDAK DITEMUKAN ]</strong><br>
                    <span style="color:#aaa; font-size:9px;"><?php echo e($imagePath); ?></span>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="padding:40px; background:#f9fafb; border:1px dashed #d1d5db; color:#9ca3af; text-align:center; font-size:11px;">
                [ TIDAK ADA FOTO TERSEDIA ]
            </div>
        <?php endif; ?>
        <p class="photo-caption">
            Foto: <?php echo e(basename($inf->foto_terbaru ?? 'tidak_ada_foto.jpg')); ?>

            &nbsp;&mdash;&nbsp; Diupload oleh: <?php echo e($inf->nama_user ?? 'Admin'); ?>

        </p>
    </div>

    
    
    
    <div class="ttd-wrapper">
        <table class="ttd-table">
            <tr>
                
                <td style="width:50%;"></td>

                
                <td style="width:50%; text-align:center;">
                    <div class="ttd-kota-tgl">Banjarmasin, <?php echo e(now()->translatedFormat('d F Y')); ?></div>
                    <div class="ttd-jabatan">Koordinator Tim Teknis</div>
                    <div class="ttd-ruang"></div>
                    <div class="ttd-nama">HIZBULWATHONI, S.T.</div>
                    <div class="ttd-nip">NIP. 19760814 200604 1 008</div>
                </td>
            </tr>
        </table>
    </div>

    
    
    
    <div class="footer">
        Dicetak melalui GEO-SINFRA &nbsp;|&nbsp; <?php echo e(now()->translatedFormat('d F Y, H:i')); ?> WITA
        &nbsp;|&nbsp; Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin
    </div>

</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views\admin\pdf-infrastruktur.blade.php ENDPATH**/ ?>