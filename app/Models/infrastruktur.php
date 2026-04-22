<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Infrastruktur extends Model
{
    // 1. Nama tabel sesuai database kamu
    protected $table = 'infrastruktur';

    // 2. Kunci utama sesuai gambar kamu
    protected $primaryKey = 'id_infrastruktur';

    // 3. Aktifkan timestamps karena ada created_at & updated_at di gambar
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi (Mass Assignment)
    // Saya urutkan persis sesuai urutan di gambar kamu
    protected $fillable = [
        'id_user',
        'id_wilayah',
        'nama_objek',
        'foto_terbaru',
        'jenis',
        'alamat',
        'latitude',
        'longitude'
    ];

    /**
     * Hubungan: Infrastruktur ini diinput oleh seorang User (Surveyor/Admin)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Hubungan: Infrastruktur ini berada di suatu Wilayah (Kelurahan)
     */
    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah');
    }

    /**
     * Hubungan: Satu data infrastruktur memiliki satu hasil analisis AI (Decision Tree)
     */
    public function analisis(): HasOne
    {
        return $this->hasOne(AnalisisAi::class, 'id_infrastruktur');
    }
}