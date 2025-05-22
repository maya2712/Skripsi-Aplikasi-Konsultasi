<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasGoogleCalendar;
use App\Models\Pesan;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable, HasGoogleCalendar;

    protected $table = 'mahasiswas';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'email',
        'password',
        'prodi_id',
        'konsentrasi_id', // Bisa null
        'role_id',
        'profile_photo',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_in',
        'google_token_created_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_access_token',
        'google_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'google_token_created_at' => 'datetime',
        'google_token_expires_in' => 'integer',
    ];

    // Relasi ke tabel role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // Relasi ke tabel prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    // Relasi ke tabel konsentrasi (nullable)
    public function konsentrasi()
    {
        return $this->belongsTo(Konsentrasi::class, 'konsentrasi_id');
    }

    // Method untuk mengecek role
    public function hasRole($roleName)
    {
        return $this->role && $this->role->role_akses === $roleName;
    }

    // Relasi dengan grup
    public function grups()
    {
        return $this->belongsToMany(Grup::class, 'grup_mahasiswa', 'mahasiswa_nim', 'grup_id', 'nim', 'id')
                    ->withTimestamps();
    }
    
    // Relasi dengan pesan yang dikirim
    public function pesanDikirim()
    {
        return $this->hasMany(Pesan::class, 'nim_pengirim', 'nim');
    }
}