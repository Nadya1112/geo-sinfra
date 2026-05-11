<?php

namespace App\Observers;

use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;

class InfrastrukturObserver
{
    /**
     * Fungsi ini akan berjalan otomatis SETIAP KALI data di tabel infrastruktur 
     * berhasil dibuat (created) atau diperbarui (updated).
     */
    public function saved(Infrastruktur $infra)
    {
        $id = $infra->id_infrastruktur;
        $kondisi = strtolower($infra->kondisi ?? '');
        $material = strtolower($infra->material_eksisting ?? '');
        $drainase = strtolower($infra->has_drainase ?? 'tidak');
        
        $skor = 0;
        $label_kondisi = 'Baik';

        // --- LOGIKA DECISION TREE (Pohon Keputusan) ---
        if (str_contains($kondisi, 'berat') || str_contains($kondisi, 'putus') || str_contains($kondisi, 'hancur')) {
            $skor = 85; 
            $label_kondisi = 'Rusak Berat';
        } 
        elseif (str_contains($kondisi, 'goyang') || str_contains($kondisi, 'retak') || str_contains($kondisi, 'banjir')) {
            $skor = 50;
            $label_kondisi = 'Rusak Sedang';
            
            if (str_contains($material, 'kayu') || str_contains($material, 'ulin')) {
                $skor += 20; 
                if ($skor >= 70) $label_kondisi = 'Rusak Berat';
            }
        } 
        elseif (str_contains($kondisi, 'ringan') || str_contains($kondisi, 'kusam')) {
            $skor = 25;
            $label_kondisi = 'Rusak Ringan';
        }

        if ($drainase == 'tidak' && $label_kondisi != 'Baik') {
            $skor += 10;
        }

        $skor = min($skor, 100);

        // Buat rekomendasi
        $rekomendasi = "Kondisi aman.";
        if ($label_kondisi == 'Rusak Berat') $rekomendasi = "Perbaikan struktur total secepatnya.";
        if ($label_kondisi == 'Rusak Sedang') $rekomendasi = "Perlu pemeliharaan dan penambalan.";

        // Simpan otomatis ke tabel analisis_ai
        DB::table('analisis_ai')->updateOrInsert(
            ['id_infrastruktur' => $id],
            [
                'param_kondisi' => $infra->kondisi,
                'skor_dt' => $skor,
                'label_prioritas' => $label_kondisi,
                'rekomendasi' => $rekomendasi,
                'status_validasi' => 'Selesai',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}