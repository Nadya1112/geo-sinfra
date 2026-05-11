<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalisisAiController extends Controller
{
    public function prosesAnalisis(Request $request, $id)
    {
        // 1. Ambil data infrastruktur mentah dari database
        $infra = DB::table('infrastruktur')->where('id_infrastruktur', $id)->first();

        if (!$infra) {
            return redirect()->back()->with('error', 'Data infrastruktur tidak ditemukan.');
        }

        // 2. Siapkan variabel untuk logika Decision Tree
        $kondisi = strtolower($infra->kondisi ?? '');
        $material = strtolower($infra->material_eksisting ?? '');
        $drainase = strtolower($infra->has_drainase ?? 'tidak');
        
        $skor = 0;
        $label_kondisi = 'Baik';

        // 3. LOGIKA DECISION TREE (Pohon Keputusan)
        // --- Cabang 1: Rusak Berat ---
        if (str_contains($kondisi, 'berat') || str_contains($kondisi, 'putus') || str_contains($kondisi, 'hancur') || str_contains($kondisi, 'amblas')) {
            $skor = 85; 
            $label_kondisi = 'Rusak Berat';
        } 
        // --- Cabang 2: Rusak Sedang ---
        elseif (str_contains($kondisi, 'goyang') || str_contains($kondisi, 'retak') || str_contains($kondisi, 'aus') || str_contains($kondisi, 'banjir')) {
            $skor = 50;
            $label_kondisi = 'Rusak Sedang';
            
            // Sub-cabang: Jika material dari kayu dan sudah goyang/retak, skor bahaya naik
            if (str_contains($material, 'kayu') || str_contains($material, 'ulin')) {
                $skor += 20; 
                if ($skor >= 70) {
                    $label_kondisi = 'Rusak Berat'; // Berubah status jika skor tinggi
                }
            }
        } 
        // --- Cabang 3: Rusak Ringan ---
        elseif (str_contains($kondisi, 'ringan') || str_contains($kondisi, 'kusam') || str_contains($kondisi, 'perlu peningkatan')) {
            $skor = 25;
            $label_kondisi = 'Rusak Ringan';
        }

        // --- Aturan Tambahan: Drainase ---
        if ($drainase == 'tidak' && $label_kondisi != 'Baik') {
            $skor += 10; // Ketiadaan drainase memperburuk keadaan
        }

        // Batasi skor maksimal 100
        $skor = min($skor, 100);

        // Buat rekomendasi otomatis
        $rekomendasi = "Kondisi aman.";
        if ($label_kondisi == 'Rusak Berat') $rekomendasi = "Perbaikan struktur total secepatnya.";
        if ($label_kondisi == 'Rusak Sedang') $rekomendasi = "Perlu pemeliharaan dan penambalan.";
        if ($label_kondisi == 'Rusak Ringan') $rekomendasi = "Lakukan pengawasan berkala.";

        // 4. Simpan hasil analisis ke tabel analisis_ai (Upsert: Update jika ada, Insert jika baru)
        DB::table('analisis_ai')->updateOrInsert(
            ['id_infrastruktur' => $id], // Cari berdasarkan ID ini
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

        // 5. Kembali ke halaman sebelumnya dengan notifikasi sukses
        return redirect()->back()->with('success', "Analisis AI Selesai! Status ditetapkan sebagai: $label_kondisi (Skor: $skor)");
    }
}