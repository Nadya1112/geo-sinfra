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

    // 4. Kolom yang bisa diisi
    protected $fillable = [
        'id_user',
        'id_wilayah',
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
     * Hubungan: Infrastruktur berada di satu Wilayah (Kelurahan)
     */
    public function wilayah(): BelongsTo
    {
        // Menyambung ke id_wilayah di tabel wilayah
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    /**
     * Hubungan: Infrastruktur diinput/dikelola oleh satu User (Admin/Surveyor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}