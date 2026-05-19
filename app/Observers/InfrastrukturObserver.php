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
    }
}