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

    // 4. Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'id_user',
        'id_kecamatan', // <--- Sudah diubah dari id_wilayah
        'nama_objek',
        'foto_terbaru',
        'jenis',
        'alamat',
        'latitude',
        'longitude',
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
     * Hubungan: Infrastruktur berada di satu Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        // Menyambung ke id_kecamatan di tabel kecamatan
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Hubungan: Infrastruktur diinput/dikelola oleh satu User (Admin/Surveyor)
     */
    public function user(): BelongsTo
    {
        // Primary Key di tabel users adalah 'id', foreign key di infrastruktur adalah 'id_user'
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}