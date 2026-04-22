<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role'])] // Tambahkan 'role' di sini
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan ini agar sinkron dengan database kamu
    protected $primaryKey = 'id_user';

    /**
     * Menentukan konversi tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Hubungan: Satu User (Surveyor) bisa menginput banyak data Infrastruktur
     */
    public function infrastrukturs(): HasMany
    {
        return $this->hasMany(Infrastruktur::class, 'id_user');
    }
}