<?php
/**
 * SKRIP IMPORT DATA INFRASTRUKTUR DARI CSV (DED)
 * ================================================
 * Skrip ini membaca file CSV pipe-delimited yang diekspor dari Excel,
 * memperbaiki format koordinat (koma → titik), dan memasukkan ke database.
 * 
 * CARA PAKAI:
 *   php artisan tinker < import_csv.php
 *   ATAU melalui route /import-ded-data
 */

// Jika dijalankan via route, fungsi ini akan dipanggil
function importDedData() {
    $csvPath = storage_path('app/import_infra.csv');
    
    if (!file_exists($csvPath)) {
        return ['error' => 'File CSV tidak ditemukan di: ' . $csvPath];
    }
    
    $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if (count($lines) < 2) {
        return ['error' => 'File CSV kosong atau hanya header'];
    }
    
    // Baris pertama = header
    $header = str_getcsv(array_shift($lines), '|', '"');
    $header = array_map(function($h) {
        return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h))); // Hapus BOM jika ada
    }, $header);
    
    // Mapping kolom header ke index
    $colMap = array_flip($header);
    
    // Validasi: pastikan kolom penting ada
    $requiredCols = ['id_kelurahan', 'latitude', 'longitude', 'nama_objek'];
    foreach ($requiredCols as $col) {
        if (!isset($colMap[$col])) {
            return ['error' => "Kolom '$col' tidak ditemukan di CSV. Kolom yang ada: " . implode(', ', $header)];
        }
    }
    
    $imported = 0;
    $skipped = 0;
    $errors = [];
    
    // Hapus data lama yang belum punya foto (berarti data impor sebelumnya)
    // PERINGATAN: Hanya uncomment baris ini jika ingin menghapus data lama!
    // \DB::table('infrastruktur')->whereNull('foto_terbaru')->orWhere('foto_terbaru', '')->delete();
    
    foreach ($lines as $lineNum => $line) {
        $row = str_getcsv($line, '|', '"');
        
        if (count($row) < count($header)) {
            $skipped++;
            $errors[] = "Baris " . ($lineNum + 2) . ": jumlah kolom tidak sesuai";
            continue;
        }
        
        // Ambil nilai per kolom
        $idKelurahan    = trim($row[$colMap['id_kelurahan']] ?? '');
        $idUser         = trim($row[$colMap['id_user'] ?? -1] ?? '4');
        $namaObjek      = trim($row[$colMap['nama_objek']] ?? '');
        $fotoTerbaru    = trim($row[$colMap['foto_terbaru'] ?? -1] ?? '');
        $jenis          = trim($row[$colMap['jenis'] ?? -1] ?? 'jalan');
        $material       = trim($row[$colMap['material_eksisting'] ?? -1] ?? '-');
        $alamat         = trim($row[$colMap['alamat'] ?? -1] ?? '');
        $latitude       = trim($row[$colMap['latitude']] ?? '');
        $longitude      = trim($row[$colMap['longitude']] ?? '');
        $kondisi        = trim($row[$colMap['kondisi'] ?? -1] ?? '-');
        $panjang        = trim($row[$colMap['panjang'] ?? -1] ?? '0');
        $lebar          = trim($row[$colMap['lebar'] ?? -1] ?? '0');
        $hasDrainase    = trim($row[$colMap['has_drainase'] ?? -1] ?? 'tidak');
        $hasGorongGorong = trim($row[$colMap['has_gorong_gorong'] ?? -1] ?? 'tidak');
        $statusVerifikasi = trim($row[$colMap['status_verifikasi'] ?? -1] ?? 'Pending');
        $tglSurvey      = trim($row[$colMap['tgl_survey'] ?? -1] ?? '');
        
        // =============================================
        // PERBAIKAN FORMAT KOORDINAT: koma → titik
        // =============================================
        $latitude  = str_replace(',', '.', $latitude);
        $longitude = str_replace(',', '.', $longitude);
        
        // Perbaikan format angka: koma → titik untuk panjang dan lebar juga
        $panjang = str_replace(',', '.', $panjang);
        $lebar   = str_replace(',', '.', $lebar);
        
        // Validasi koordinat
        if (empty($latitude) || empty($longitude) || !is_numeric($latitude) || !is_numeric($longitude)) {
            $skipped++;
            $errors[] = "Baris " . ($lineNum + 2) . " ($namaObjek): koordinat tidak valid (lat=$latitude, lng=$longitude)";
            continue;
        }
        
        // Validasi id_kelurahan
        if (empty($idKelurahan) || !is_numeric($idKelurahan)) {
            $skipped++;
            $errors[] = "Baris " . ($lineNum + 2) . " ($namaObjek): id_kelurahan tidak valid ($idKelurahan)";
            continue;
        }
        
        // Konversi boolean
        $hasDrainaseVal = (strtolower($hasDrainase) === 'ya' || $hasDrainase === '1') ? 1 : 0;
        $hasGorongVal   = (strtolower($hasGorongGorong) === 'ya' || $hasGorongGorong === '1') ? 1 : 0;
        
        // Tentukan tanggal survey
        $tglSurveyDate = null;
        if (!empty($tglSurvey)) {
            try {
                $tglSurveyDate = date('Y-m-d', strtotime($tglSurvey));
                if ($tglSurveyDate === '1970-01-01') $tglSurveyDate = null;
            } catch (\Exception $e) {
                $tglSurveyDate = null;
            }
        }
        
        // Cek duplikat berdasarkan nama_objek + koordinat (cegah double import)
        $exists = \DB::table('infrastruktur')
            ->where('nama_objek', $namaObjek)
            ->where('latitude', $latitude)
            ->where('longitude', $longitude)
            ->whereNull('deleted_at')
            ->exists();
        
        if ($exists) {
            $skipped++;
            continue; // Skip duplikat tanpa error
        }
        
        // INSERT ke database
        try {
            \DB::table('infrastruktur')->insert([
                'id_kelurahan'       => (int)$idKelurahan,
                'id_user'            => (int)$idUser,
                'nama_objek'         => $namaObjek,
                'nama_infrastruktur' => $namaObjek,
                'foto_terbaru'       => $fotoTerbaru ?: null,
                'jenis'              => $jenis ?: 'jalan',
                'jenis_infrastruktur'=> $jenis ?: 'jalan',
                'material_eksisting' => $material ?: '-',
                'alamat'             => $alamat ?: null,
                'latitude'           => $latitude,
                'longitude'          => $longitude,
                'kondisi'            => $kondisi ?: '-',
                'panjang'            => (float)$panjang,
                'lebar'              => (float)$lebar,
                'has_drainase'       => $hasDrainaseVal,
                'has_gorong_gorong'  => $hasGorongVal,
                'status_verifikasi'  => $statusVerifikasi ?: 'Pending',
                'tgl_survey'         => $tglSurveyDate,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
            $imported++;
        } catch (\Exception $e) {
            $skipped++;
            $errors[] = "Baris " . ($lineNum + 2) . " ($namaObjek): " . $e->getMessage();
        }
    }
    
    return [
        'success'  => true,
        'imported' => $imported,
        'skipped'  => $skipped,
        'total'    => count($lines),
        'errors'   => array_slice($errors, 0, 20), // Tampilkan maks 20 error
    ];
}
