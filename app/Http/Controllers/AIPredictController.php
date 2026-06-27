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
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Gambar gagal diunggah! Mungkin ukuran file terlalu besar (melebihi limit server PHP) atau format tidak didukung.'
            ], 400);
        }

        // 1. Validasi Input Manual Total (Bypass Laravel Validator yang mungkin butuh finfo)
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json([
                'success' => false,
                'error' => 'Gambar gagal diunggah! Pastikan ukuran file tidak melebihi batas.'
            ], 400);
        }

        // Cek ekstensi manual tanpa fileinfo
        $file = $request->file('image');
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['jpeg', 'jpg', 'png', 'webp'];

        if (!in_array($extension, $allowedExtensions)) {
            return response()->json([
                'success' => false,
                'error' => 'Format file tidak didukung! Gunakan JPG atau PNG.'
            ], 400);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $absolutePath = storage_path('app/temp_predict/' . $filename);
        if (!file_exists(storage_path('app/temp_predict'))) {
            mkdir(storage_path('app/temp_predict'), 0777, true);
        }

        try {
            // 2. Simpan sementara menggunakan native PHP untuk menghindari Flysystem (yang butuh finfo)
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $absolutePath)) {
                return response()->json(['success' => false, 'error' => 'Gagal menyimpan file secara internal.'], 500);
            }

            // 3. Kirim ke Flask API via HTTP POST menggunakan Native cURL untuk menghindari finfo (Guzzle)
            $apiUrl = env('CNN_API_URL', 'http://127.0.0.1:5000/predict');
            
            $mimeType = 'image/' . ($extension == 'jpg' ? 'jpeg' : $extension);
            $cfile = new \CURLFile($absolutePath, $mimeType, $filename);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['image' => $cfile]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $responseBody = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // 4. Cek respons dari Flask API
            if ($responseBody === false || $httpcode !== 200) {
                Log::error('AI Prediction cURL Error: ' . $curlError . ' Body: ' . $responseBody);
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal terhubung ke Server AI. Status: ' . $httpcode . ' Error: ' . $curlError
                ], 500);
            }

            return response()->json(json_decode($responseBody, true));

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
