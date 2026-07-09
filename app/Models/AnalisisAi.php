<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalisisAi extends Model
{
    use SoftDeletes;

    // 1. Nama tabel di database
    protected $table = 'analisis_ai';

    // 2. Kunci utama (Primary Key)
    protected $primaryKey = 'id_ai';

    // 3. Aktifkan timestamps
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi
    protected $fillable = [
        'id_infrastruktur',
        'id_tim_teknis',
        'param_kondisi',
        'param_kepadatan',
        'skor_dt',
        'label_prioritas',
        'rekomendasi',
        'catatan_validasi',
        'status_validasi',
        'tgl_validasi'
    ];

    /**
     * 5. Casting Tipe Data
     */
    protected $casts = [
        'skor_dt' => 'float',
        'tgl_validasi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Analisis ini merujuk pada satu data Infrastruktur
     */
    public function infrastruktur(): BelongsTo
    {
        // Menyambung ke id_infrastruktur di tabel infrastruktur
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur', 'id_infrastruktur');
    }

    /**
     * OTOMATIS: Kalkulasi Hybrid (DT + CNN)
     */
    public static function calculateHybrid($id)
    {
        $infra = \App\Models\Infrastruktur::find($id);
        if (!$infra) return;

        // 1. Ambil Parameter Dasar (DT)
        $kondisi = strtolower($infra->kondisi ?? '');
        $material = strtolower($infra->material_eksisting ?? '');
        $drainase = strtolower($infra->has_drainase ?? 'tidak');
        
        // 2. Ambil Hasil Prediksi Visual (CNN)
        $cnn = \Illuminate\Support\Facades\DB::table('citra_cnn')->where('id_infrastruktur', $id)->first();
        $skorCnn = $cnn ? $cnn->skor_cnn : 0; // Ini adalah persentase keyakinan AI (Confidence)
        $labelCnn = $cnn ? $cnn->label_kondisi : 'Baik'; // Ini adalah tebakan asli AI

        // 3. Konversi label CNN menjadi Base Score Keparahan
        $skor = 0;
        if ($labelCnn == 'Rusak Berat' || $labelCnn == 'Berat') {
            $skor = 80;
        } elseif ($labelCnn == 'Rusak Sedang' || $labelCnn == 'Sedang') {
            $skor = 45;
        } elseif ($labelCnn == 'Rusak Ringan' || $labelCnn == 'Ringan') {
            $skor = 25;
        } else {
            $skor = 10; // Baik
        }
        
        // 4. Analisis Teks (NLP Decision Tree) — Penyesuaian dari laporan lapangan
        if (preg_match('/(hancur|putus|total|amblas|parah|longsor|roboh|hilang|berat)/', $kondisi)) {
            $skor += 55;
        } elseif (preg_match('/(retak|lubang|goyang|rusak|tergenang|bolong|lapuk|sedang|lepas)/', $kondisi)) {
            $skor += 25;
        } elseif (preg_match('/(ringan|kusam|minor|sedikit)/', $kondisi)) {
            $skor += 10;
        }

        // 5. Analisis Parameter Lingkungan
        if ($drainase == 'tidak') $skor += 5;
        if (str_contains($material, 'tanah') || str_contains($material, 'kayu') || str_contains($material, 'ulin')) $skor += 5;
        
        // Pastikan skor berada dalam range 0 - 100
        $skor = min(max(round($skor), 0), 100);

        // 6. Penentuan Label Akhir berdasarkan Total Skor Gabungan (Hybrid)
        if ($skor >= 65) {
            $label = 'Rusak Berat';
            $rekom = "PRIORITAS UTAMA: Deteksi gabungan visual & laporan lapangan menunjukkan kerusakan kritis. Segera rehabilitasi.";
        } elseif ($skor >= 35) {
            $label = 'Rusak Sedang';
            $rekom = "Perlu pemeliharaan rutin dan perbaikan pada area terdampak visual.";
        } else {
            $label = 'Baik';
            $rekom = "Kondisi terkendali, lakukan pemantauan berkala.";
        }

        // 6. Simpan/Update Hasil
        return self::updateOrInsert(
            ['id_infrastruktur' => $id],
            [
                'param_kondisi' => $infra->kondisi,
                'skor_dt' => $skor,
                'label_prioritas' => $label,
                'rekomendasi' => $rekom,
                'status_validasi' => 'Selesai',
                'updated_at' => now(),
                'created_at' => now()
            ]
        );
    }
}