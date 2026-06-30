<?php

namespace App\Http\Controllers;

use App\Models\LaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PublicReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if (!$request->hasFile('foto') || !$request->file('foto')->isValid()) {
            return back()->withErrors(['foto' => 'Gambar gagal diunggah.']);
        }

        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        
        // Simpan manual untuk menghindari Flysystem dan finfo
        $publicLaporanPath = storage_path('app/public/laporan_warga');
        if (!file_exists($publicLaporanPath)) {
            mkdir($publicLaporanPath, 0777, true);
        }
        $absolutePath = $publicLaporanPath . '/' . $filename;
        move_uploaded_file($_FILES['foto']['tmp_name'], $absolutePath);
        
        $fotoPath = 'laporan_warga/' . $filename;

        $laporan = LaporanWarga::create([
            'nama_pelapor' => $request->nama_pelapor,
            'no_hp' => $request->no_hp,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Menunggu',
        ]);

        // Analisis AI Otomatis
        $this->analyzeWithAI($laporan);

        // Format Nomor WhatsApp Admin
        $target = \App\Helpers\SettingHelper::get('fonnte_target');
        $waLink = null;
        
        if (!empty($target)) {
            // Ubah awalan 0 menjadi 62
            if (str_starts_with($target, '0')) {
                $target = '62' . substr($target, 1);
            }

            $pesan = "*🚨 LAPORAN KERUSAKAN BARU (GEO-SINFRA)*\n\n";
            $pesan .= "*Pelapor:* {$laporan->nama_pelapor}\n";
            $pesan .= "*No. WA:* {$laporan->no_hp}\n";
            $pesan .= "*Deskripsi:* {$laporan->deskripsi}\n\n";
            $pesan .= "*Analisis AI (Awal):*\n";
            $pesan .= "- Kondisi: {$laporan->label_ai}\n";
            $pesan .= "- Kategori: " . ucfirst($laporan->jenis_ai) . "\n\n";
            $pesan .= "Mohon segera ditindaklanjuti.";

            $waLink = 'https://wa.me/' . $target . '?text=' . urlencode($pesan);
        }

        return redirect('/')->with('success_laporan', 'Laporan Anda telah tercatat di sistem kami! Silakan klik tombol di bawah untuk meneruskan laporan ini langsung ke WhatsApp Admin.')
                            ->with('wa_link', $waLink);
    }

    private function analyzeWithAI($laporan)
    {
        try {
            $filePath = storage_path('app/public/' . $laporan->foto);
            
            if (!file_exists($filePath)) {
                return $this->simulateCnnAnalysis($laporan);
            }

            $apiUrl = env('CNN_API_URL', 'http://127.0.0.1:5000/predict');
            
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeType = 'image/' . ($extension == 'jpg' ? 'jpeg' : $extension);
            $cfile = new \CURLFile($filePath, $mimeType, basename($filePath));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['image' => $cfile]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $output = curl_exec($ch);
            curl_close($ch);

            $result = json_decode(trim($output), true);

            if ($result && isset($result['success']) && $result['success'] == true) {
                $skorKondisi = isset($result['confidence_kondisi']) ? ($result['confidence_kondisi'] / 100) : 0;
                $labelKondisi = $result['kondisi'] ?? 'Tidak Terdeteksi';

                $predictedJenisDisplay = $result['jenis'] ?? 'Jalan';
                $jenisMapping = [
                    'Jalan' => 'jalan',
                    'Jembatan' => 'jembatan',
                    'Titian' => 'titian'
                ];
                $predictedJenisDb = $jenisMapping[$predictedJenisDisplay] ?? 'jalan';

                $laporan->update([
                    'skor_ai' => $skorKondisi,
                    'label_ai' => $labelKondisi,
                    'jenis_ai' => $predictedJenisDb,
                ]);
            } else {
                return $this->simulateCnnAnalysis($laporan);
            }
        } catch (\Exception $e) {
            Log::error("Error saat menghubungi API CNN untuk Laporan Warga: " . $e->getMessage());
            return $this->simulateCnnAnalysis($laporan);
        }
    }

    private function simulateCnnAnalysis($laporan)
    {
        $kondisiRaw = strtolower($laporan->deskripsi ?? '');

        if (preg_match('/(hancur|putus|total|amblas|parah|longsor|roboh|hilang)/', $kondisiRaw)) {
            $simLabel = 'Rusak Berat';
            $simSkor = rand(65, 98) / 100;
        } elseif (preg_match('/(retak|lubang|goyang|rusak|tergenang|bolong|lapuk|ringan|sedikit)/', $kondisiRaw)) {
            $simLabel = 'Rusak Sedang';
            $simSkor = rand(35, 60) / 100;
        } else {
            $simLabel = 'Baik';
            $simSkor = rand(0, 30) / 100;
        }

        // Simulasi deteksi jenis dari teks deskripsi
        $simJenisDb = 'jalan';
        if (preg_match('/(jembatan)/', $kondisiRaw)) {
            $simJenisDb = 'jembatan';
        } elseif (preg_match('/(titian|kayu ulin)/', $kondisiRaw)) {
            $simJenisDb = 'titian';
        }

        $laporan->update([
            'skor_ai' => $simSkor,
            'label_ai' => $simLabel,
            'jenis_ai' => $simJenisDb,
        ]);
    }
}
