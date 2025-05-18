<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasGoogleCalendar;
use App\Models\Pesan;

class Mahasiswa extends Authenticatable
{
    use HasGoogleCalendar;
    use HasFactory, Notifiable;
    protected $primaryKey = 'nim';
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'email',
        'password',
        'prodi_id',
        'konsentrasi_id',
        'role_id',
        'profile_photo',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_in',
        'google_token_created_at'
    ];

    protected $hidden = [
        'password',
        'google_access_token',
        'google_refresh_token'
    ];

    protected $casts = [
        'google_token_created_at' => 'datetime',
        'google_token_expires_in' => 'integer',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function konsentrasi()
    {
        return $this->belongsTo(Konsentrasi::class);
    }

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