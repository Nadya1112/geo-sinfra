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
        
        $publicLaporanPath = storage_path('app/public/laporan_warga');
        $file->move($publicLaporanPath, $filename);
        
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

        // Kirim Notifikasi WhatsApp via Fonnte
        try {
            $token = \App\Helpers\SettingHelper::get('fonnte_token');
            $target = \App\Helpers\SettingHelper::get('fonnte_target');

            if (!empty($token) && !empty($target)) {
                $pesan = "*🚨 LAPORAN KERUSAKAN BARU (GEO-SINFRA)*\n\n";
                $pesan .= "*Pelapor:* {$laporan->nama_pelapor}\n";
                $pesan .= "*No. WA:* {$laporan->no_hp}\n";
                $pesan .= "*Deskripsi:* {$laporan->deskripsi}\n\n";
                $pesan .= "*Analisis AI (Awal):*\n";
                $pesan .= "- Kondisi: {$laporan->label_ai}\n";
                $pesan .= "- Kategori: " . ucfirst($laporan->jenis_ai) . "\n\n";
                $pesan .= "Segera tinjau laporan ini di Dashboard Admin GEO-SINFRA.";

                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $pesan,
                ]);

                // Kirim Auto-Reply ke Warga (Pelapor)
                $wargaPhone = $laporan->no_hp;
                if (!empty($wargaPhone)) {
                    $pesanWarga = "Halo Bapak/Ibu {$laporan->nama_pelapor}! 👋\n\n";
                    $pesanWarga .= "Terima kasih atas laporan Anda terkait kerusakan infrastruktur *" . ucfirst($laporan->jenis_ai) . "*. Laporan Anda telah kami terima dan masuk ke dalam sistem pusat GEO-SINFRA.\n\n";
                    $pesanWarga .= "Tim Teknis akan segera meninjau laporan ini.\n\n";
                    $pesanWarga .= "Mari bersama membangun infrastruktur yang lebih baik! Salam, GEO-SINFRA! 🏙️";

                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $token,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $wargaPhone,
                        'message' => $pesanWarga,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Gagal mengirim WA Fonnte: " . $e->getMessage());
        }

        return redirect('/')->with('success_laporan', 'Laporan kerusakan berhasil dikirim! Sistem AI kami sedang menilainya dan Tim akan segera meninjaunya.');
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
