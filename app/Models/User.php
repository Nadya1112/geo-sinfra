<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel (Opsional jika namanya 'users')
     */
    protected $table = 'users';

    /**
     * Primary Key. 
     * Jika di phpMyAdmin kamu menggunakan 'id_user', ubah 'id' menjadi 'id_user'.
     */
    protected $primaryKey = 'id'; 

    /**
     * Kolom yang bisa diisi secara massal.
     * Pastikan kolom 'role' ada di database (admin, surveyor, tim_teknis).
     */
    protected $fillable = [
        'name', 
        'email', 
        'no_hp',
        'password', 
        'role', 
        'id_kecamatan',
        'profile_photo'
    ];

    /**
     * Kolom yang disembunyikan saat data diubah menjadi JSON/Array.
     */
    protected $hidden = [
        'password', 
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Hubungan: Satu User (Surveyor/Admin) bisa menginput banyak Infrastruktur.
     */
    public function infrastrukturs(): HasMany
    {
        // 'id_user' adalah nama foreign key di tabel infrastruktur
        return $this->hasMany(Infrastruktur::class, 'id_user', 'id');
    }

    /**
     * Hubungan: User (Surveyor) memiliki banyak wilayah tugas (Kecamatan).
     */
    public function kecamatans(): BelongsToMany
    {
        return $this->belongsToMany(Kecamatan::class, 'user_kecamatan', 'id_user', 'id_kecamatan')->withTimestamps();
    }

    /**
     * Hubungan: User (Surveyor) memiliki satu wilayah tugas (Kecamatan) - Legacy helper
     */
    public function kecamatan()
    {
        return $this->belongsTo(\App\Models\Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Method tambahan untuk cek Role (Sangat berguna untuk Middleware/Akses Dashboard)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSurveyor(): bool
    {
        return $this->role === 'surveyor';
    }

    public function isTimTeknis(): bool
    {
        return $this->role === 'tim_teknis';
    }
}