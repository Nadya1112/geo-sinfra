<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanWarga extends Model
{
    protected $table = 'laporan_warga';

    protected $fillable = [
        'id_infrastruktur',
        'nama_pelapor',
        'no_hp',
        'deskripsi',
        'foto',
        'latitude',
        'longitude',
        'status',
        'skor_ai',
        'label_ai',
        'jenis_ai',
    ];

    public function infrastruktur(): BelongsTo
    {
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur', 'id_infrastruktur');
    }
}
