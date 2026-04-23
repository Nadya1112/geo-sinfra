<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilayah extends Model
{
    use SoftDeletes;

    // 1. Menentukan nama tabel
    protected $table = 'wilayah';

    // 2. Menentukan kunci utama (Primary Key)
    protected $primaryKey = 'id_wilayah';

    // 3. Mengaktifkan timestamps
    public $timestamps = true;

    // 4. Daftar kolom yang bisa diisi
    protected $fillable = [
        'id_kelurahan',
        'geometri',
        'warna'
    ];

    /**
     * 5. Casting Tipe Data
     * Sangat penting agar kolom JSON 'geometri' otomatis menjadi array PHP.
     * Ini memudahkan saat pengiriman data ke Leaflet.js (GeoJSON).
     */
    protected $casts = [
        'geometri' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Wilayah ini dimiliki oleh satu Kelurahan
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    /**
     * Hubungan: Satu wilayah bisa memiliki banyak objek infrastruktur
     */
    public function infrastrukturs(): HasMany
    {
        // Pastikan di tabel 'infrastruktur' terdapat kolom 'id_wilayah'
        return $this->hasMany(Infrastruktur::class, 'id_wilayah', 'id_wilayah');
    }
}