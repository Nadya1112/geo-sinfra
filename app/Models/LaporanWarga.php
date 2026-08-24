<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanWarga extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $table = 'laporan_warga';

    protected $fillable = [
        'uuid',
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
        'id_surveyor',
    ];

    public function infrastruktur(): BelongsTo
    {
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur', 'id_infrastruktur');
    }

    public function surveyor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_surveyor', 'id');
    }
}
