<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

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
     * Pastikan kolom 'role' ada di database (admin, surveyor, kabid).
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
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
        'deleted_at' => 'datetime',
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

    public function isKabid(): bool
    {
        return $this->role === 'kabid';
    }
}