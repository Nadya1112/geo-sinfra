<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AIPredictController extends Controller
{
    /**
     * Menerima request upload gambar dan meneruskannya ke script Python predict.py
     */
    public function predict(Request $request)
    {
        // 1. Validasi Input Gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maksimal 5MB
        ]);

        try {
            // 2. Simpan sementara gambar yang diupload ke storage/app/public/temp_predict
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan ke storage local Laravel
            $path = $file->storeAs('temp_predict', $filename, 'local');
            
            // Dapatkan absolute path file yang tersimpan dengan menggunakan fungsi Storage
            $absolutePath = Storage::disk('local')->path($path);

            // 3. Konfigurasi path ke script Python
            $pythonPath = 'python'; // Pastikan 'python' ada di environment variable server
            $scriptPath = base_path('predict.py');

            // 4. Eksekusi script Python
            // Gunakan escapeshellarg untuk memastikan path dibaca dengan benar di Windows/Linux
            $argScript = escapeshellarg($scriptPath);
            $argImage = escapeshellarg($absolutePath);
            $command = "$pythonPath $argScript $argImage";
            
            // Eksekusi dan tangkap outputnya
            $output = shell_exec($command . ' 2>&1'); // 2>&1 untuk menangkap error message juga

            // 5. Hapus file gambar sementara setelah selesai diprediksi
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }

            // 6. Parsing output JSON dari Python
            // Output dari predict.py sudah diset menggunakan format JSON yang rapi
            if (empty($output)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Tidak ada respon dari script AI (Output kosong).'
                ], 500);
            }

            $result = json_decode(trim($output), true);

            // Jika JSON gagal diparsing (misal karena ada warning di Python yang tercetak)
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Catat ke log untuk debugging
                Log::error('AI Prediction JSON Decode Error: ' . json_last_error_msg());
                Log::error('Raw Output: ' . $output);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal membaca format balasan dari AI.',
                    'raw_output' => $output // Menampilkan output asli untuk mempermudah perbaikan
                ], 500);
            }

            // 7. Kembalikan hasil prediksi ke Frontend
            if (isset($result['success']) && $result['success'] == true) {
                return response()->json($result, 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Terjadi kesalahan tidak diketahui pada proses prediksi AI.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('AI Prediction Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
