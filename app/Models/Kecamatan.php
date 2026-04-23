<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kecamatan extends Model
{
    use SoftDeletes;

    // 1. Nama tabel di database
    protected $table = 'kecamatan';

    // 2. Kunci utama tabel
    protected $primaryKey = 'id_kecamatan';

    // 3. Aktifkan timestamps karena kita sudah menambahkannya di SQL tadi
    public $timestamps = true;

    // 4. Kolom yang boleh diisi secara massal
    protected $fillable = [
        'nama_kecamatan',
        'geometri',
        'warna',
    ];

    /**
     * 5. Casting Tipe Data
     * Sangat penting agar kolom JSON 'geometri' otomatis menjadi array PHP
     */
    protected $casts = [
        'geometri' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Satu Kecamatan memiliki banyak Kelurahan
     */
    public function kelurahans(): HasMany
    {
        // Relasi ke model Kelurahan menggunakan id_kecamatan sebagai foreign key
        return $this->hasMany(Kelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }
}