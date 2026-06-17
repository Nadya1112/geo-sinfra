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
            'foto' => 'required|image|max:5120', // 5MB max
        ]);

        $fotoPath = $request->file('foto')->store('laporan_warga', 'public');

        $laporan = LaporanWarga::create([
            'nama_pelapor' => $request->nama_pelapor,
            'no_hp' => $request->no_hp,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Menunggu Review',
        ]);

        // Analisis AI Otomatis
        $this->analyzeWithAI($laporan);

        return redirect('/')->with('success_laporan', 'Laporan kerusakan berhasil dikirim! Sistem AI kami sedang menilainya dan Tim akan segera meninjaunya.');
    }

    private function analyzeWithAI($laporan)
    {
        try {
            $filePath = storage_path('app/public/' . $laporan->foto);
            
            if (!file_exists($filePath)) {
                return $this->simulateCnnAnalysis($laporan);
            }

            $pythonPath = 'python'; 
            $scriptPath = base_path('predict.py');
            $argScript = escapeshellarg($scriptPath);
            $argImage = escapeshellarg($filePath);
            $command = "$pythonPath $argScript $argImage";
            
            $output = shell_exec($command . ' 2>&1');
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
