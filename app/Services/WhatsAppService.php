<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Infrastruktur;

class WhatsAppService
{
    /**
     * Send an emergency WhatsApp notification via Fonnte Gateway
     */
    public static function sendDaruratNotification(Infrastruktur $infra)
    {
        $token = env('FONNTE_TOKEN');
        $targetNumber = env('TIM_TEKNIS_WA_NUMBER'); // The Tim Teknis's WhatsApp Number

        if (empty($token) || empty($targetNumber)) {
            Log::warning('Fonnte Token atau Nomor WA Tim Teknis belum diset di .env');
            return false;
        }

        $namaObjek = $infra->nama_objek ?? '-';
        $jenis = strtoupper($infra->jenis ?? '-');
        $lokasi = ($infra->kelurahan->nama_kelurahan ?? '-') . ', Kec. ' . ($infra->kelurahan->kecamatan->nama_kecamatan ?? '-');
        $pelapor = $infra->user->name ?? 'Sistem';
        $statusAI = $infra->analisis->label_prioritas ?? 'Rusak Berat';
        $link = route('tim_teknis.prioritas');

        $message = "🚨 *URGENT: PERINGATAN DARURAT INFRASTRUKTUR* 🚨\n\n";
        $message .= "Yth. Bapak/Ibu Tim Teknis,\n";
        $message .= "Sistem mendeteksi adanya laporan infrastruktur dengan tingkat kerusakan *SANGAT BERAT* yang membutuhkan perhatian segera.\n\n";
        $message .= "📋 *DETAIL LAPORAN:*\n";
        $message .= "▪️ *Nama Objek:* $namaObjek\n";
        $message .= "▪️ *Jenis:* $jenis\n";
        $message .= "▪️ *Lokasi:* $lokasi\n";
        $message .= "▪️ *Pelapor:* $pelapor\n";
        $message .= "▪️ *Status AI:* *$statusAI*\n\n";
        $message .= "Silakan klik link berikut untuk melihat detail dan melakukan validasi:\n";
        $message .= $link;

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp Fonnte dikirim ke ' . $targetNumber);
                return true;
            } else {
                Log::error('Gagal mengirim WhatsApp Fonnte: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a notification to Tim Teknis when a report needs to be validated (ACC)
     */
    public static function sendApprovalNotification(Infrastruktur $infra)
    {
        $token = env('FONNTE_TOKEN');
        $targetNumber = env('TIM_TEKNIS_WA_NUMBER');

        if (empty($token) || empty($targetNumber)) {
            Log::warning('Fonnte Token atau Nomor WA Tim Teknis belum diset di .env');
            return false;
        }

        $namaObjek = $infra->nama_objek ?? '-';
        $lokasi = ($infra->kelurahan->nama_kelurahan ?? '-') . ', Kec. ' . ($infra->kelurahan->kecamatan->nama_kecamatan ?? '-');
        $pelapor = $infra->user->name ?? 'Surveyor';
        $link = route('tim_teknis.validasi');

        $message = "🔔 *PEMBERITAHUAN VALIDASI (ACC) SINFRA* 🔔\n\n";
        $message .= "Yth. Bapak/Ibu Tim Teknis,\n";
        $message .= "Terdapat laporan infrastruktur baru yang telah diverifikasi oleh Admin dan menunggu *Validasi (ACC)* dari Bapak/Ibu.\n\n";
        $message .= "📋 *DETAIL LAPORAN:*\n";
        $message .= "▪️ *Nama Objek:* $namaObjek\n";
        $message .= "▪️ *Lokasi:* $lokasi\n";
        $message .= "▪️ *Pelapor:* $pelapor\n\n";
        $message .= "Silakan klik link berikut untuk meninjau dan memvalidasi usulan tersebut:\n";
        $message .= $link;

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp Fonnte (ACC) dikirim ke ' . $targetNumber);
                return true;
            } else {
                Log::error('Gagal mengirim WA Fonnte (ACC): ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error Fonnte (ACC) Exception: ' . $e->getMessage());
            return false;
        }
    public static function sendValidationResultNotification(Infrastruktur $infra)
    {
        $token = env('FONNTE_TOKEN');
        $targetNumber = $infra->user->no_hp ?? null; // Nomor HP Surveyor

        if (empty($token) || empty($targetNumber)) {
            Log::warning('Fonnte Token belum diset atau nomor HP Surveyor kosong.');
            return false;
        }

        $namaObjek = $infra->nama_objek ?? '-';
        $status = $infra->status_validasi === 'Validated' ? 'DISETUJUI (ACC)' : 'DITOLAK / PERLU PERBAIKAN';
        $alasan = $infra->alasan_penolakan ?? 'Tidak ada catatan tambahan.';
        
        $message = "🔔 *HASIL VALIDASI LAPORAN SINFRA* 🔔\n\n";
        $message .= "Yth. Bapak/Ibu Surveyor,\n";
        $message .= "Berikut adalah hasil validasi Tim Teknis terhadap laporan Anda:\n\n";
        $message .= "📋 *DETAIL LAPORAN:*\n";
        $message .= "▪️ *Nama Objek:* $namaObjek\n";
        $message .= "▪️ *Status Validasi:* *$status*\n";
        $message .= "▪️ *Catatan/Alasan:* $alasan\n\n";
        
        if ($infra->status_validasi === 'Validated') {
            $message .= "Laporan telah disetujui dan akan segera ditindaklanjuti untuk perbaikan fisik. Terima kasih atas partisipasi Anda.";
        } else {
            $message .= "Mohon periksa kembali laporan Anda dan sesuaikan dengan catatan yang diberikan.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $targetNumber,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp Validation Result dikirim ke Surveyor ' . $targetNumber);
                return true;
            } else {
                Log::error('Gagal mengirim WA Validation Result: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error Fonnte Validation Result Exception: ' . $e->getMessage());
            return false;
        }
    }
}
