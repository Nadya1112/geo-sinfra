<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    // 1. Beritahu Laravel nama tabel aslinya
    protected $table = 'kecamatan';

    // 2. Beritahu Laravel nama kunci utamanya (karena bukan 'id')
    protected $primaryKey = 'id_kecamatan';

    // 3. Matikan pencatatan waktu otomatis (created_at/updated_at) 
    // karena data wilayah biasanya bersifat tetap
    public $timestamps = false;

    // 4. Daftar kolom yang boleh diisi (untuk keamanan data)
    protected $fillable = [
        'nama_kecamatan',
        'warna'
    ];

    /**
     * Hubungan: Satu Kecamatan memiliki banyak Kelurahan
     */
    public function kelurahans(): HasMany
    {
        // 'id_kecamatan' di sini adalah "kabel" penghubung di tabel kelurahan
        return $this->hasMany(Kelurahan::class, 'id_kecamatan');
    }
}