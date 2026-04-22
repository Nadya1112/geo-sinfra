<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitraCnn extends Model
{
    // 1. Nama tabel di database
    protected $table = 'citra_cnn';

    // 2. Kunci utama (Primary Key)
    protected $primaryKey = 'id_citra';

    // 3. Aktifkan timestamps untuk mencatat waktu pemrosesan AI
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi
    protected $fillable = [
        'id_infrastruktur',
        'skor_cnn',
        'label_kondisi'
    ];

    /**
     * Hubungan: Hasil CNN ini merujuk pada satu data Infrastruktur (Foto)
     */
    public function infrastruktur(): BelongsTo
    {
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur');
    }
}