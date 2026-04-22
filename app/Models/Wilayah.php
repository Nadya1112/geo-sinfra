<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    // 1. Menentukan nama tabel
    protected $table = 'wilayah';

    // 2. Menentukan kunci utama (Primary Key)
    protected $primaryKey = 'id_wilayah';

    // 3. Mengaktifkan timestamps (karena di tabel ada created_at & updated_at)
    public $timestamps = true;

    // 4. Daftar kolom yang bisa diisi
    protected $fillable = [
        'id_kelurahan',
        'geometri',
        'warna'
    ];

    /**
     * Hubungan: Wilayah ini dimiliki oleh satu Kelurahan
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan');
    }

    /**
     * Hubungan: Satu wilayah bisa memiliki banyak objek infrastruktur
     */
    public function infrastrukturs(): HasMany
    {
        return $this->hasMany(Infrastruktur::class, 'id_wilayah');
    }
}