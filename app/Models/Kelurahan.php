<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kelurahan extends Model
{
    protected $table = 'kelurahan';
    protected $primaryKey = 'id_kelurahan';
    
    // Sama seperti Kecamatan, kita matikan timestamps jika tidak ada di tabel
    public $timestamps = false;

    protected $fillable = [
        'id_kecamatan',
        'nama_kelurahan'
    ];

    /**
     * Hubungan: Kelurahan ini termasuk dalam satu Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }

    /**
     * Hubungan: Satu Kelurahan memiliki satu data Geometri (untuk Peta)
     */
    public function wilayah(): HasOne
    {
        return $this->hasOne(Wilayah::class, 'id_kelurahan');
    }
}