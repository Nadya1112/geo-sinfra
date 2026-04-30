<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CitraCnn extends Model
{
    use SoftDeletes;

    // 1. Nama tabel di database
    protected $table = 'citra_cnn';

    // 2. Kunci utama (Primary Key)
    protected $primaryKey = 'id_citra';

    // 3. Aktifkan timestamps
    public $timestamps = true;

    // 4. Daftar kolom yang boleh diisi
    protected $fillable = [
        'id_infrastruktur',
        'skor_cnn',
        'label_kondisi'
    ];

    /**
     * 5. Casting Tipe Data
     * Skor CNN biasanya berupa angka desimal (probabilitas), 
     * jadi kita cast ke float agar presisinya terjaga.
     */
    protected $casts = [
        'skor_cnn' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Hubungan: Hasil CNN ini merujuk pada satu data Infrastruktur
     */
    public function infrastruktur(): BelongsTo
    {
        // Menyambung ke id_infrastruktur di tabel infrastruktur
        return $this->belongsTo(Infrastruktur::class, 'id_infrastruktur', 'id_infrastruktur');
    }
}