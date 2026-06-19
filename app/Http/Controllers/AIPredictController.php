<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class AIPredictController extends Controller
{
    /**
     * Menerima request upload gambar dan meneruskannya ke Flask API AI (ai_bridge.py)
     */
    public function predict(Request $request)
    {
        // 1. Validasi Input Gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Maksimal 5MB
        ]);

        $absolutePath = null;
        try {
            // 2. Simpan sementara gambar yang diupload ke storage/app/public/temp_predict
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('temp_predict', $filename, 'local');
            $absolutePath = Storage::disk('local')->path($path);

            // 3. Kirim ke Flask API via HTTP POST
            $apiUrl = env('CNN_API_URL', 'http://127.0.0.1:5000/predict');
            
            $response = Http::timeout(30)->attach(
                'image', file_get_contents($absolutePath), $filename
            )->post($apiUrl);

            // 4. Cek respons dari Flask API
            if ($response->failed()) {
                Log::error('AI Prediction API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal terhubung ke Server AI. Pastikan ai_bridge.py sudah berjalan.'
                ], 500);
            }

            $result = $response->json();

            // 5. Kembalikan hasil prediksi ke Frontend
            if (isset($result['success']) && $result['success'] == true) {
                return response()->json($result, 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Terjadi kesalahan pada proses prediksi AI.'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('AI Prediction Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        } finally {
            // 6. Selalu hapus file gambar sementara, baik sukses maupun error (Mencegah Memory Leak)
            if ($absolutePath && file_exists($absolutePath)) {
                @unlink($absolutePath);
            }
        }
    }
}
