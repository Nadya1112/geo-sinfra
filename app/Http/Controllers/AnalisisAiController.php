<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalisisAiController extends Controller
{
    /**
     * Fungsi utama untuk menjalankan logika Decision Tree
     */
    public function hitungDecisionTree($kondisi_mentah, $material, $drainase)
    {
        // 1. Ubah teks ke huruf kecil semua agar mudah dideteksi
        $kondisi = strtolower($kondisi_mentah);
        
        $skor = 0;
        $label_kondisi = 'Baik';

        // 2. Cabang Logika Utama (Berdasarkan Kata Kunci dari Data DED kamu)
        
        // --- KATEGORI RUSAK BERAT ---
        if (str_contains($kondisi, 'berat') || str_contains($kondisi, 'putus') || str_contains($kondisi, 'hancur') || str_contains($kondisi, 'amblas')) {
            $skor = 80; // Skor kerusakan tinggi
            $label_kondisi = 'Rusak Berat';
        } 
        // --- KATEGORI RUSAK SEDANG ---
        elseif (str_contains($kondisi, 'goyang') || str_contains($kondisi, 'retak') || str_contains($kondisi, 'aus') || str_contains($kondisi, 'banjir')) {
            $skor = 50;
            $label_kondisi = 'Rusak Sedang';
            
            // Logika tambahan (Cabang turunan): 
            // Jika material kayu dan retak/goyang, itu lebih rawan dari beton
            if (str_contains(strtolower($material), 'kayu') || str_contains(strtolower($material), 'ulin')) {
                $skor += 15; 
                // Jika skor naik jadi 65, kita bisa naikkan statusnya
                if ($skor > 60) {
                    $label_kondisi = 'Rusak Berat';
                }
            }
        } 
        // --- KATEGORI RUSAK RINGAN ---
        elseif (str_contains($kondisi, 'ringan') || str_contains($kondisi, 'kusam') || str_contains($kondisi, 'perlu peningkatan')) {
            $skor = 20;
            $label_kondisi = 'Rusak Ringan';
        }

        // 3. Cabang Logika Tambahan: Faktor Drainase
        // Infrastruktur tanpa drainase mempercepat kerusakan (skor kerusakan bertambah)
        if ($drainase == 'tidak') {
            $skor += 10;
        }

        // Pastikan skor maksimal tidak lebih dari 100
        $skor = min($skor, 100);

        // 4. Kembalikan Hasilnya
        return [
            'skor_dt' => $skor,
            'label_prioritas' => $label_kondisi,
            'rekomendasi' => $this->buatRekomendasi($label_kondisi)
        ];
    }

    private function buatRekomendasi($label)
    {
        if ($label == 'Rusak Berat') return "Perlu perbaikan struktur secara total dan segera.";
        if ($label == 'Rusak Sedang') return "Perlu pemeliharaan rutin dan penambalan material.";
        return "Kondisi masih layak fungsi, lakukan pengawasan berkala.";
    }
}