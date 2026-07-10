<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Darurat Infrastruktur</title>
    <link rel="icon" href="<?php echo e(asset('logo_geo-sinfra.png')); ?>" type="image/png">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #334e68; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #be123c; color: white; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; }
        .content p { line-height: 1.6; margin-bottom: 20px; }
        .details { background-color: #fff1f2; border: 1px solid #ffe4e6; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .details h3 { color: #be123c; margin-top: 0; margin-bottom: 15px; font-size: 16px; border-bottom: 1px solid #fecdd3; padding-bottom: 10px; }
        .row { margin-bottom: 10px; display: flex; }
        .label { font-weight: bold; min-width: 120px; color: #475569; }
        .value { color: #0f172a; font-weight: bold; }
        .btn-container { text-align: center; margin-top: 30px; }
        .btn { display: inline-block; background-color: #0f172a; color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; text-transform: uppercase; font-size: 14px; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
<style>
    @media (min-width: 768px) { html { zoom: 0.9 !important; } }
    @media (max-width: 767px) { html { zoom: 0.5 !important; } }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Peringatan Darurat Infrastruktur</h1>
        </div>
        <div class="content">
            <p>Yth. Bapak/Ibu Tim Teknis,</p>
            <p>Sistem <strong>GEO-SINFRA</strong> dan Analisis AI mendeteksi adanya laporan infrastruktur baru dengan tingkat kerusakan <strong>SANGAT BERAT / KRITIS</strong> yang membutuhkan perhatian segera.</p>
            
            <div class="details">
                <h3>Detail Laporan</h3>
                <div class="row">
                    <div class="label">Nama Objek:</div>
                    <div class="value"><?php echo e($infrastruktur->nama_objek ?? '-'); ?></div>
                </div>
                <div class="row">
                    <div class="label">Jenis:</div>
                    <div class="value" style="text-transform: uppercase;"><?php echo e($infrastruktur->jenis ?? '-'); ?></div>
                </div>
                <div class="row">
                    <div class="label">Lokasi:</div>
                    <div class="value"><?php echo e($infrastruktur->kelurahan->nama_kelurahan ?? '-'); ?>, Kec. <?php echo e($infrastruktur->kelurahan->kecamatan->nama_kecamatan ?? '-'); ?></div>
                </div>
                <div class="row">
                    <div class="label">Pelapor:</div>
                    <div class="value"><?php echo e($infrastruktur->user->name ?? 'Sistem'); ?></div>
                </div>
                <div class="row">
                    <div class="label">Status AI:</div>
                    <div class="value" style="color: #be123c;"><?php echo e($infrastruktur->analisis->label_prioritas ?? 'Rusak Berat'); ?></div>
                </div>
            </div>

            <p>Mohon segera menindaklanjuti dan memvalidasi laporan ini melalui portal Executive WebGIS atau menu Rekomendasi Prioritas.</p>

            <div class="btn-container">
                <a href="<?php echo e(route('tim_teknis.prioritas')); ?>" class="btn">Lihat Detail di Aplikasi</a>
            </div>
        </div>
        <div class="footer">
            Email ini dihasilkan secara otomatis oleh GEO-SINFRA Artificial Intelligence System.<br>
            Pemerintah Kota Banjarmasin
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon1\laragon\www\geo-sinfra\resources\views\emails\darurat_notification.blade.php ENDPATH**/ ?>