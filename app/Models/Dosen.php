<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use App\Traits\HasGoogleCalendar;
use App\Models\Grup;
use App\Models\Pesan;

class Dosen extends Authenticatable
{
    use HasGoogleCalendar;
    use HasFactory, Notifiable;
    protected $primaryKey = 'nip';
    protected $keyType = 'string';

    protected $fillable = [
        'nip',
        'nama',
        'nama_singkat',
        'email',
        'password',
        'prodi_id',
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
        return $this->belongsTo(Role::class,'role_id', 'id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }

    // Relasi dengan grup
    public function grups()
    {
        return $this->hasMany(Grup::class, 'dosen_id', 'nip');
    }
    
    // Relasi dengan pesan yang diterima
    public function pesanDiterima()
    {
        return $this->hasMany(Pesan::class, 'nip_penerima', 'nip');
    }
}