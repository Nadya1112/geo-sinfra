<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'id_kelurahan',
        'id_kecamatan',
        'nama_infrastruktur',
        'jenis_infrastruktur',
        'foto_terbaru',
        'latitude',
        'longitude',
        'kondisi',
        'status_verifikasi',
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

    /**
     * Hubungan: Infrastruktur memiliki satu hasil Analisis AI (Decision Tree)
     */
    public function analisis(): HasOne
    {
        return $this->hasOne(AnalisisAi::class, 'id_infrastruktur', 'id_infrastruktur');
    }

    /**
     * Hubungan: Infrastruktur memiliki satu hasil Analisis Citra (CNN)
     */
    public function cnn(): HasOne
    {
        return $this->hasOne(CitraCnn::class, 'id_infrastruktur', 'id_infrastruktur');
    }
}