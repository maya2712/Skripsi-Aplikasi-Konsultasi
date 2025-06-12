<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasGoogleCalendar;
use App\Models\Pesan;
use App\Models\Grup;
use App\Models\Role;
use App\Models\Prodi;
use App\Models\Konsentrasi;

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
        'profile_photo', // Kolom untuk foto profil
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

    // ========== PROFILE PHOTO METHODS ==========
    
    /**
     * Accessor untuk URL foto profil
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && Storage::disk('public')->exists('profile_photos/' . $this->profile_photo)) {
            return asset('storage/profile_photos/' . $this->profile_photo);
        }
        return null;
    }

    /**
     * Method untuk check apakah ada foto custom
     */
    public function hasCustomPhoto()
    {
        return $this->profile_photo && Storage::disk('public')->exists('profile_photos/' . $this->profile_photo);
    }

    /**
     * Method untuk mendapatkan inisial nama untuk avatar default
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->nama);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break; // Maksimal 2 huruf
            }
        }
        
        return $initials ?: 'M'; // Default 'M' untuk Mahasiswa
    }

    /**
     * Method untuk menghapus foto profil
     */
    public function deleteProfilePhoto()
    {
        if ($this->profile_photo && Storage::disk('public')->exists('profile_photos/' . $this->profile_photo)) {
            Storage::disk('public')->delete('profile_photos/' . $this->profile_photo);
        }
        $this->update(['profile_photo' => null]);
    }

    // ========== RELASI ==========
    
    /**
     * Relasi ke tabel role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Relasi ke tabel prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    /**
     * Relasi ke tabel konsentrasi (nullable)
     */
    public function konsentrasi()
    {
        return $this->belongsTo(Konsentrasi::class, 'konsentrasi_id');
    }

    /**
     * Relasi dengan grup (many-to-many)
     */
    public function grups()
    {
        return $this->belongsToMany(Grup::class, 'grup_mahasiswa', 'mahasiswa_nim', 'grup_id', 'nim', 'id')
                    ->withTimestamps();
    }
    
    /**
     * Relasi dengan pesan yang dikirim
     */
    public function pesanDikirim()
    {
        return $this->hasMany(Pesan::class, 'nim_pengirim', 'nim');
    }

    /**
     * Relasi dengan usulan bimbingan
     */
    public function usulanBimbingan()
    {
        return $this->hasMany(\App\Models\UsulanBimbingan::class, 'nim', 'nim');
    }

    // ========== METHODS ==========
    
    /**
     * Method untuk mengecek role
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->role_akses === $roleName;
    }

    /**
     * Method untuk mendapatkan nama lengkap dengan NIM
     */
    public function getFullIdentityAttribute()
    {
        return $this->nama . ' (' . $this->nim . ')';
    }

    /**
     * Method untuk mendapatkan status angkatan
     */
    public function getAngkatanStatusAttribute()
    {
        $currentYear = date('Y');
        $yearsSinceEntry = $currentYear - $this->angkatan;
        
        if ($yearsSinceEntry <= 4) {
            return 'Aktif';
        } elseif ($yearsSinceEntry <= 6) {
            return 'Perpanjangan';
        } else {
            return 'Alumni/Lulus';
        }
    }

    /**
     * Scope untuk filter berdasarkan angkatan
     */
    public function scopeAngkatan($query, $year)
    {
        return $query->where('angkatan', $year);
    }

    /**
     * Scope untuk filter berdasarkan prodi
     */
    public function scopeProdi($query, $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    /**
     * Scope untuk filter berdasarkan konsentrasi
     */
    public function scopeKonsentrasi($query, $konsentrasiId)
    {
        return $query->where('konsentrasi_id', $konsentrasiId);
    }

    /**
     * Scope untuk pencarian berdasarkan nama atau NIM
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'LIKE', '%' . $search . '%')
              ->orWhere('nim', 'LIKE', '%' . $search . '%')
              ->orWhere('email', 'LIKE', '%' . $search . '%');
        });
    }

    /**
     * Method untuk mendapatkan statistik mahasiswa
     */
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'aktif' => self::whereRaw('YEAR(CURDATE()) - angkatan <= 4')->count(),
            'perpanjangan' => self::whereRaw('YEAR(CURDATE()) - angkatan BETWEEN 5 AND 6')->count(),
            'alumni' => self::whereRaw('YEAR(CURDATE()) - angkatan > 6')->count(),
        ];
    }

    // ========== GOOGLE CALENDAR INTEGRATION ==========
    
    /**
     * Method untuk check apakah Google Calendar sudah terhubung
     */
    public function hasGoogleCalendarConnection()
    {
        return !empty($this->google_access_token) && !empty($this->google_refresh_token);
    }

    /**
     * Method untuk check apakah token Google masih valid
     */
    public function isGoogleTokenValid()
    {
        if (!$this->google_token_created_at || !$this->google_token_expires_in) {
            return false;
        }

        $expiresAt = $this->google_token_created_at->addSeconds($this->google_token_expires_in);
        return now()->lt($expiresAt);
    }

    // ========== GRUP FEATURES ==========
    
    /**
     * Method untuk mendapatkan pesan grup yang belum dibaca
     */
    public function getUnreadGroupMessagesCount()
    {
        $unreadCount = 0;
        
        foreach ($this->grups as $grup) {
            $unreadCount += $grup->unreadMessages; // Menggunakan accessor dari model Grup
        }
        
        return $unreadCount;
    }

    /**
     * Method untuk mendapatkan grup dengan pesan belum dibaca
     */
    public function getGroupsWithUnreadMessages()
    {
        return $this->grups->filter(function ($grup) {
            return $grup->unreadMessages > 0;
        });
    }
}