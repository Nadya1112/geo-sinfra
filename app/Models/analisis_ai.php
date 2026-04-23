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
    protected $primaryKey = 'id_analisis';

    // 3. Aktifkan timestamps
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi
    protected $fillable = [
        'id_infrastruktur',
        'skor_kerusakan',
        'label_prioritas',
        'status_validasi',
        'catatan_kabid',
        'tgl_validasi'
    ];

    /**
     * 5. Casting Tipe Data
     * Mengonversi skor menjadi float dan tanggal validasi menjadi objek Carbon
     */
    protected $casts = [
        'skor_kerusakan' => 'float',
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
}