<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Grup extends Model
{
    use HasFactory;
    
    protected $table = 'grups';
    
    protected $fillable = [
        'nama_grup',
        'dosen_id'
    ];
    
    // Relasi dengan dosen (pembuat grup)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'nip');
    }
    
    // Relasi dengan mahasiswa
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'grup_mahasiswa', 'grup_id', 'mahasiswa_nim', 'id', 'nim')
                    ->withTimestamps();
    }
    
    // Relasi dengan pesan grup
    public function pesan()
    {
        return $this->hasMany(GrupPesan::class, 'grup_id')->orderBy('created_at', 'asc');
    }
    
    // Accessor untuk menghitung jumlah pesan belum dibaca
    public function getUnreadMessagesAttribute()
    {
        if (!Auth::check()) {
            return 0;
        }
        
        $user = Auth::user();
        $userId = null;
        $userType = null;
        
        if (Auth::guard('dosen')->check()) {
            $userId = $user->nip;
            $userType = 'dosen';
        } elseif (Auth::guard('mahasiswa')->check()) {
            $userId = $user->nim;
            $userType = 'mahasiswa';
        }
        
        if (!$userId || !$userType) {
            return 0;
        }
        
        // Hitung pesan yang belum dibaca oleh user ini
        $unreadCount = GrupPesan::where('grup_id', $this->id)
            ->where('pengirim_id', '!=', $userId) // Jangan hitung pesan yang dikirim sendiri
            ->whereNotExists(function ($query) use ($userId, $userType) {
                $query->select(DB::raw(1))
                    ->from('grup_pesan_reads')
                    ->whereRaw('grup_pesan_reads.grup_pesan_id = grup_pesan.id')
                    ->where('user_id', $userId)
                    ->where('user_type', $userType)
                    ->where('read', true);
            })
            ->count();
            
        return $unreadCount;
    }
}