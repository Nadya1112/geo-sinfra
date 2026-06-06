<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\AiProcessingTrait;

class AnalisisAiController extends Controller
{
    use AiProcessingTrait;
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
        
        // 2b. Ambil skor dari analisis CNN (Opsi A)
        $cnn = DB::table('citra_cnn')->where('id_infrastruktur', $id)->first();
        
        // JIKA CNN BELUM ADA TAPI FOTO ADA, PROSES SEKARANG
        if (!$cnn && $infra->foto_terbaru && $infra->foto_terbaru != 'default.jpg') {
            $this->processCnnAnalysis($id, $infra->foto_terbaru);
            $cnn = DB::table('citra_cnn')->where('id_infrastruktur', $id)->first();
        }

        $skorCnn = $cnn ? $cnn->skor_cnn : 0;
        $labelCnn = $cnn ? $cnn->label_kondisi : 'Tidak Ada Data Visual';
        
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

        // --- Aturan Tambahan: Integrasi CNN ---
        // Jika CNN mendeteksi kerusakan tinggi (> 0.7), tingkatkan skor DT
        if ($skorCnn > 0.7) {
            $skor += 15;
        }

        // Batasi skor maksimal 100
        $skor = min($skor, 100);

        // Gabungkan label (SPK Hybrid)
        if ($skor >= 65 || $skorCnn >= 0.65) {
            $label_kondisi = 'Rusak Berat';
        } elseif ($skor >= 35 || $skorCnn >= 0.35) {
            $label_kondisi = 'Rusak Sedang';
        } else {
            $label_kondisi = 'Baik';
        }

        // Buat rekomendasi otomatis
        $rekomendasi = "Kondisi terpantau aman.";
        if ($label_kondisi == 'Rusak Berat') {
            $rekomendasi = "PRIORITAS UTAMA: Struktur kritis terdeteksi secara visual (" . round($skorCnn * 100) . "%) dan teknis. Segera lakukan rehabilitasi.";
        } elseif ($label_kondisi == 'Rusak Sedang') {
            $rekomendasi = "Perlu pemeliharaan rutin dan perbaikan pada area terdampak visual.";
        } elseif ($label_kondisi == 'Baik') {
            $rekomendasi = "Kondisi terkendali, lakukan pemantauan berkala.";
        }

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