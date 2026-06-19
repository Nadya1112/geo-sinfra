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
        
        // 3. LOGIKA DECISION TREE (Pohon Keputusan) -> DIALIHKAN KE PYTHON AI BRIDGE
        $spkApiUrl = env('SPK_API_URL', 'http://127.0.0.1:5000/predict-spk');
        
        $spkResponse = \Illuminate\Support\Facades\Http::timeout(10)->post($spkApiUrl, [
            'kondisi' => $kondisi,
            'material' => $material,
            'drainase' => $drainase,
            'skor_cnn' => floatval($skorCnn)
        ]);

        if ($spkResponse->successful() && $spkResponse->json('success')) {
            $data = $spkResponse->json();
            $skor = $data['skor'] ?? 0;
            $label_kondisi = $data['label'] ?? 'Baik';
            $rekomendasi = $data['rekomendasi'] ?? "Kondisi terpantau aman.";
        } else {
            // Fallback ringan jika API Python mati untuk SPK
            \Illuminate\Support\Facades\Log::error("API SPK Error: " . $spkResponse->body());
            $skor = 0;
            $label_kondisi = 'Baik';
            $rekomendasi = "Menunggu analisis lebih lanjut.";
            
            // Fallback manual sederhana jika sangat terdesak
            if (str_contains($kondisi, 'berat') || $skorCnn >= 0.65) {
                $skor = 80;
                $label_kondisi = 'Rusak Berat';
            }
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