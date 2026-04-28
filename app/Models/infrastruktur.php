<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infrastruktur extends Model
{
    use SoftDeletes;

    // 1. Nama tabel
    protected $table = 'infrastruktur';

    // 2. Primary Key
    protected $primaryKey = 'id_infrastruktur';

    // 3. Timestamps aktif
    public $timestamps = true;

    /**
     * 4. Kolom yang bisa diisi (Mass Assignment)
     * Disesuaikan dengan controller: nama_infrastruktur, jenis_infrastruktur, id_kelurahan, kondisi
     */
    protected $fillable = [
        'id_user',
        'id_kelurahan',      // Mengacu pada wilayah terkecil (Kelurahan)
        'nama_infrastruktur', // Nama kolom yang benar (bukan nama_objek)
        'jenis_infrastruktur',// Nama kolom yang benar (bukan jenis)
        'foto',              // Nama field untuk dokumentasi lapangan
        'latitude',
        'longitude',
        'kondisi',           // Untuk menyimpan status: Baik, Rusak Ringan, Rusak Berat
    ];

    /**
     * 5. Casting Tipe Data
     */
    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Infrastruktur berada di satu Kelurahan
     * Melalui kelurahan, kita bisa mendapatkan data Kecamatan (Infrastruktur -> Kelurahan -> Kecamatan)
     */
    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'id_kelurahan', 'id_kelurahan');
    }

    /**
     * Hubungan: Infrastruktur diinput/dikelola oleh satu User (Admin/Surveyor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}