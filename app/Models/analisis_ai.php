<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalisisAi extends Model
{
    // 1. Nama tabel di database kamu
    protected $table = 'analisis_ai';

    // 2. Kunci utama (Primary Key)
    protected $primaryKey = 'id_analisis';

    // 3. Aktifkan timestamps untuk mencatat kapan analisis dilakukan
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi (Mass Assignment)
    // Termasuk kolom validasi untuk Kabid yang kita tambahkan tadi
    protected $fillable = [
        'id_infrastruktur',
        'skor_kerusakan',
        'label_prioritas',
        'status_validasi',
        'catatan_kabid',
        'tgl_validasi'
    ];

    /**
     * Hubungan: Analisis ini merujuk pada satu data Infrastruktur
     */
    public function infrastruktur(): BelongsTo
    {
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur');
    }
}