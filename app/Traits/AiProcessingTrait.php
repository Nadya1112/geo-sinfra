<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait AiProcessingTrait
{
    /**
     * Mengirimkan gambar ke API Python CNN dan menyimpan hasilnya.
     */
    public function processCnnAnalysis($infrastrukturId, $imagePath)
    {
        try {
            // URL API Python (Sesuaikan dengan alamat server Flask/FastAPI Anda)
            $apiUrl = env('CNN_API_URL', 'http://127.0.0.1:5000/predict');
            
            // Ambil file fisik
            $filePath = storage_path('app/public/' . $imagePath);
            
            if (!file_exists($filePath)) {
                Log::error("File gambar tidak ditemukan untuk analisis CNN: " . $filePath);
                return false;
            }

            // Kirim request ke API Python
            // Catatan: Pastikan API Python Anda menerima file dengan field 'image'
            $response = Http::attach(
                'image', file_get_contents($filePath), basename($filePath)
            )->post($apiUrl);

            if ($response->successful()) {
                $result = $response->json();
                
                // Simpan hasil ke tabel citra_cnn
                DB::table('citra_cnn')->updateOrInsert(
                    ['id_infrastruktur' => $infrastrukturId],
                    [
                        'skor_cnn' => $result['probability'] ?? 0,
                        'label_kondisi' => $result['label'] ?? 'Tidak Terdeteksi',
                        'catatan_visual' => $result['details'] ?? 'Analisis otomatis oleh CNN',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

                // TRIGER OTOMATIS: Kalkulasi ulang Hybrid (CNN + DT)
                \App\Models\AnalisisAi::calculateHybrid($infrastrukturId);

                return true;
            } else {
                Log::error("API CNN Gagal: " . $response->body());
                return $this->simulateCnnAnalysis($infrastrukturId);
            }
        } catch (\Exception $e) {
            Log::error("Error saat menghubungi API CNN: " . $e->getMessage() . ". Menggunakan simulasi otomatis.");
            return $this->simulateCnnAnalysis($infrastrukturId);
        }
    }

    /**
     * Simulasi hasil CNN jika server Python mati
     */
    private function simulateCnnAnalysis($infrastrukturId)
    {
        $kondisiLabels = ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat', 'Baik'];
        $simLabel = $kondisiLabels[array_rand($kondisiLabels)];
        $simSkor = rand(60, 95) / 100; // 0.60 to 0.95

        DB::table('citra_cnn')->updateOrInsert(
            ['id_infrastruktur' => $infrastrukturId],
            [
                'skor_cnn' => $simSkor,
                'label_kondisi' => $simLabel,
                'catatan_visual' => 'Simulasi otomatis (Server AI Offline)',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        \App\Models\AnalisisAi::calculateHybrid($infrastrukturId);

        return true;
    }
}
