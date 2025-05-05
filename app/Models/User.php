<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Grup;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'nim',
        'role',
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

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getIdentifierAttribute()
    {
        if ($this->isMahasiswa()) {
            return $this->nim;
        } elseif ($this->isDosen()) {
            return $this->nip;
        } else {
            return $this->email; // untuk admin
        }
    }
    
    // Grup yang dibuat oleh dosen
    public function grups()
    {
        return $this->hasMany(Grup::class, 'dosen_id');
    }
    
    // Grup dimana user menjadi anggota (untuk mahasiswa)
    public function grupAnggota()
    {
        return $this->belongsToMany(Grup::class, 'grup_mahasiswa', 'mahasiswa_id', 'grup_id')
                    ->withTimestamps();
    }
}