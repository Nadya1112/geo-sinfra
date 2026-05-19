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
        'id_kabid',
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
        
        // 2. Ambil Skor CNN (Visual)
        $cnn = \Illuminate\Support\Facades\DB::table('citra_cnn')->where('id_infrastruktur', $id)->first();
        $skorCnn = $cnn ? $cnn->skor_cnn : 0;

        // 3. Logika Decision Tree (DT)
        $skor = 0;
        if (str_contains($kondisi, 'berat') || str_contains($kondisi, 'putus') || str_contains($kondisi, 'total')) {
            $skor = 85;
        } elseif (str_contains($kondisi, 'sedang') || str_contains($kondisi, 'retak') || str_contains($kondisi, 'lubang')) {
            $skor = 50;
            if (str_contains($material, 'kayu') || str_contains($material, 'ulin')) $skor += 15;
        } else {
            $skor = 20;
        }

        // 4. Integrasi CNN (Meningkatkan Akurasi Visual)
        if ($skorCnn > 0.7) $skor += 15;
        $skor = min($skor, 100);

        // 5. Penentuan Label Akhir
        if ($skor >= 80 || $skorCnn > 0.85) {
            $label = 'Rusak Berat';
            $rekom = "PRIORITAS UTAMA: Deteksi visual (" . round($skorCnn * 100) . "%) menunjukkan kerusakan kritis. Segera rehabilitasi.";
        } elseif ($skor >= 40 || $skorCnn > 0.5) {
            $label = 'Rusak Sedang';
            $rekom = "Perlu pemeliharaan rutin dan perbaikan pada area terdampak visual.";
        } else {
            $label = 'Rusak Ringan';
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