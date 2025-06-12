<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Support\Facades\Storage;
use App\Traits\HasGoogleCalendar;
use App\Models\Grup;
use App\Models\Pesan;
use App\Models\Role;
use App\Models\Prodi;

class Dosen extends Authenticatable
{
    use HasGoogleCalendar;
    use HasFactory, Notifiable;
    
    protected $primaryKey = 'nip';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nip',
        'nama',
        'nama_singkat',
        'email',
        'password',
        'prodi_id',
        'role_id',
        'jabatan_fungsional',
        'profile_photo', // Kolom untuk foto profil
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_in',
        'google_token_created_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_access_token',
        'google_refresh_token'
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
        
        return $initials ?: 'D'; // Default 'D' untuk Dosen
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
        return $this->belongsTo(Role::class,'role_id', 'id');
    }

    /**
     * Relasi ke tabel prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    /**
     * Relasi dengan grup (one-to-many)
     */
    public function grups()
    {
        return $this->hasMany(Grup::class, 'dosen_id', 'nip');
    }
    
    /**
     * Relasi dengan pesan yang diterima
     */
    public function pesanDiterima()
    {
        return $this->hasMany(Pesan::class, 'nip_penerima', 'nip');
    }

    /**
     * Relasi dengan pesan yang dikirim oleh dosen
     */
    public function pesanDikirim()
    {
        return $this->hasMany(Pesan::class, 'nip_pengirim', 'nip');
    }

    /**
     * Relasi dengan jadwal bimbingan
     */
    public function jadwalBimbingan()
    {
        return $this->hasMany(\App\Models\JadwalBimbingan::class, 'nip', 'nip');
    }

    /**
     * Relasi dengan usulan bimbingan
     */
    public function usulanBimbingan()
    {
        return $this->hasMany(\App\Models\UsulanBimbingan::class, 'nip', 'nip');
    }

    // ========== METHODS ==========
    
    /**
     * Method untuk mengecek role
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Method untuk mendapatkan nama lengkap dengan NIP
     */
    public function getFullIdentityAttribute()
    {
        return $this->nama . ' (' . $this->nip . ')';
    }

    /**
     * Method untuk mendapatkan gelar/jabatan
     */
    public function getGelarJabatanAttribute()
    {
        $gelar = '';
        
        if (!empty($this->jabatan_fungsional)) {
            $gelar = $this->jabatan_fungsional;
        }
        
        return $gelar ?: 'Dosen';
    }

    /**
     * Scope untuk filter berdasarkan prodi
     */
    public function scopeProdi($query, $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    /**
     * Scope untuk filter berdasarkan jabatan fungsional
     */
    public function scopeJabatan($query, $jabatan)
    {
        return $query->where('jabatan_fungsional', $jabatan);
    }

    /**
     * Scope untuk pencarian berdasarkan nama atau NIP
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'LIKE', '%' . $search . '%')
              ->orWhere('nama_singkat', 'LIKE', '%' . $search . '%')
              ->orWhere('nip', 'LIKE', '%' . $search . '%')
              ->orWhere('email', 'LIKE', '%' . $search . '%');
        });
    }

    /**
     * Method untuk mendapatkan statistik dosen
     */
    public static function getStatistics()
    {
        return [
            'total' => self::count(),
            'profesor' => self::where('jabatan_fungsional', 'Profesor')->count(),
            'lektor_kepala' => self::where('jabatan_fungsional', 'Lektor Kepala')->count(),
            'lektor' => self::where('jabatan_fungsional', 'Lektor')->count(),
            'asisten_ahli' => self::where('jabatan_fungsional', 'Asisten Ahli')->count(),
            'pengajar' => self::where('jabatan_fungsional', 'Pengajar')->count(),
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

    // ========== GRUP & PESAN FEATURES ==========
    
    /**
     * Method untuk mendapatkan total pesan yang diterima
     */
    public function getTotalPesanDiterimaAttribute()
    {
        return $this->pesanDiterima()->count();
    }

    /**
     * Method untuk mendapatkan total pesan yang belum dibaca
     */
    public function getTotalPesanBelumDibacaAttribute()
    {
        return $this->pesanDiterima()->where('status_baca_dosen', false)->count();
    }

    /**
     * Method untuk mendapatkan pesan yang di-bookmark
     */
    public function getPesanBookmarked()
    {
        return $this->pesanDiterima()->where('is_bookmarked', true)->get();
    }

    /**
     * Method untuk mendapatkan pesan yang disematkan
     */
    public function getPesanPinned()
    {
        return $this->pesanDiterima()->where('is_pinned', true)->get();
    }

    /**
     * Method untuk mendapatkan grup dengan pesan baru
     */
    public function getGroupsWithNewMessages()
    {
        return $this->grups->filter(function ($grup) {
            return $grup->hasNewMessagesForDosen($this->nip);
        });
    }

    /**
     * Method untuk mendapatkan total grup yang dikelola
     */
    public function getTotalGrupAttribute()
    {
        return $this->grups()->count();
    }

    /**
     * Method untuk mendapatkan total mahasiswa dalam semua grup
     */
    public function getTotalMahasiswaInGroupsAttribute()
    {
        $totalMahasiswa = 0;
        foreach ($this->grups as $grup) {
            $totalMahasiswa += $grup->mahasiswa()->count();
        }
        return $totalMahasiswa;
    }

    // ========== JADWAL & BIMBINGAN FEATURES ==========
    
    /**
     * Method untuk mendapatkan jadwal yang tersedia
     */
    public function getJadwalTersedia()
    {
        return $this->jadwalBimbingan()
                   ->where('status', 'tersedia')
                   ->where('waktu_mulai', '>', now())
                   ->orderBy('waktu_mulai')
                   ->get();
    }

    /**
     * Method untuk mendapatkan jadwal yang sudah terisi
     */
    public function getJadwalTerisi()
    {
        return $this->jadwalBimbingan()
                   ->where('status', 'terisi')
                   ->orderBy('waktu_mulai', 'desc')
                   ->get();
    }

    /**
     * Method untuk mendapatkan usulan bimbingan yang perlu disetujui
     */
    public function getUsulanPerluPersetujuan()
    {
        return $this->usulanBimbingan()
                   ->where('status', 'USULAN')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Method untuk mendapatkan bimbingan yang sudah selesai
     */
    public function getBimbinganSelesai()
    {
        return $this->usulanBimbingan()
                   ->where('status', 'SELESAI')
                   ->orderBy('tanggal', 'desc')
                   ->get();
    }

    /**
     * Method untuk mendapatkan dashboard statistics
     */
    public function getDashboardStats()
    {
        return [
            'total_pesan_diterima' => $this->getTotalPesanDiterimaAttribute(),
            'pesan_belum_dibaca' => $this->getTotalPesanBelumDibacaAttribute(),
            'total_grup' => $this->getTotalGrupAttribute(),
            'total_mahasiswa_grup' => $this->getTotalMahasiswaInGroupsAttribute(),
            'jadwal_tersedia' => $this->getJadwalTersedia()->count(),
            'usulan_perlu_persetujuan' => $this->getUsulanPerluPersetujuan()->count(),
            'bimbingan_bulan_ini' => $this->usulanBimbingan()
                                         ->where('status', 'SELESAI')
                                         ->whereMonth('tanggal', now()->month)
                                         ->whereYear('tanggal', now()->year)
                                         ->count(),
        ];
    }

    // ========== UTILITY METHODS ==========
    
    /**
     * Method untuk format nama dengan gelar
     */
    public function getFormattedNameAttribute()
    {
        return $this->nama . ($this->jabatan_fungsional ? ', ' . $this->jabatan_fungsional : '');
    }

    /**
     * Method untuk mendapatkan warna avatar berdasarkan inisial
     */
    public function getAvatarColorAttribute()
    {
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
        ];
        
        $index = ord($this->initials[0]) % count($colors);
        return $colors[$index];
    }
}