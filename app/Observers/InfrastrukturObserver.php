<?php

namespace App\Observers;

use App\Models\Infrastruktur;
use Illuminate\Support\Facades\DB;

class InfrastrukturObserver
{
    use \App\Traits\AiProcessingTrait;

    /**
     * Fungsi ini akan berjalan otomatis SETIAP KALI data di tabel infrastruktur 
     * berhasil dibuat (created) atau diperbarui (updated).
     */
    public function saved(Infrastruktur $infra)
    {
        // 1. Cek apakah sudah ada hasil CNN, jika belum coba proses (jika ada foto)
        $cnn = DB::table('citra_cnn')->where('id_infrastruktur', $infra->id_infrastruktur)->first();
        if (!$cnn && $infra->foto_terbaru && $infra->foto_terbaru != 'default.jpg') {
            $this->processCnnAnalysis($infra->id_infrastruktur, $infra->foto_terbaru);
        }

        // 2. Panggil otomatis logika Hybrid (DT + CNN)
        \App\Models\AnalisisAi::calculateHybrid($infra->id_infrastruktur);

        // 3. Cek hasil analisis AI, jika Rusak Berat, kirim Notifikasi
        $analisis = \App\Models\AnalisisAi::where('id_infrastruktur', $infra->id_infrastruktur)->first();
        if ($analisis && str_contains(strtolower($analisis->label_prioritas), 'berat')) {
            // Load relasi agar data di email lengkap
            $infra->load(['kelurahan.kecamatan', 'user', 'analisis']);
            
            // A. Kirim Email
            try {
                $emailTujuan = env('KABID_EMAIL', 'nadiabjm412@gmail.com');
                \Illuminate\Support\Facades\Mail::to($emailTujuan)->send(new \App\Mail\DaruratNotificationMail($infra));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim Email Darurat: ' . $e->getMessage());
            }

            // B. Kirim WhatsApp Fonnte
            \App\Services\WhatsAppService::sendDaruratNotification($infra);
        }
    }
}