<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
    use SoftDeletes;

    // 1. Nama tabel di database
    protected $table = 'kelurahan';

    // 2. Kunci utama tabel
    protected $primaryKey = 'id_kelurahan';

    // 3. Aktifkan timestamps (created_at & updated_at)
    public $timestamps = true;

    // 4. Kolom yang boleh diisi (Hapus 'warna' dari sini)
    protected $fillable = [
        'id_kecamatan',
        'nama_kelurahan',
        'geometri',
    ];

    /**
     * 5. Casting Tipe Data
     * Mengonversi JSON 'geometri' otomatis menjadi array PHP
     */
    protected $casts = [
        'geometri' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Kelurahan ini termasuk dalam satu Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Hubungan: Satu Kelurahan memiliki banyak data Infrastruktur
     */
    public function infrastrukturs(): HasMany
    {
        return $this->hasMany(Infrastruktur::class, 'id_kelurahan', 'id_kelurahan');
    }
}