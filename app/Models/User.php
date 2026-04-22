<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Pastikan ini sesuai dengan nama kolom di phpMyAdmin kamu.
     * Jika di DB namanya 'id_user', ganti jadi 'id_user'.
     */
    protected $primaryKey = 'id'; 

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'remember_token'
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Hubungan ke tabel Infrastruktur
     */
    public function infrastrukturs(): HasMany
    {
        // 'id_user' di sini adalah nama kolom di tabel infrastruktur
        return $this->hasMany(Infrastruktur::class, 'id_user');
    }
}