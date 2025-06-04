<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BalasanPesan extends Model
{
    use HasFactory;
    
    protected $table = 'balasan_pesan';
    
    protected $fillable = [
        'id_pesan',
        'pengirim_id',
        'tipe_pengirim', // 'mahasiswa' atau 'dosen'
        'isi_balasan',
        'lampiran',
        'dibaca',
        'is_pinned' // TAMBAHAN UNTUK FITUR PIN
    ];
    
    protected $casts = [
        'dibaca' => 'boolean',
        'is_pinned' => 'boolean', // TAMBAHAN UNTUK FITUR PIN
    ];
    
    // Relasi ke pesan
    public function pesan()
    {
        return $this->belongsTo(Pesan::class, 'id_pesan');
    }
    
    // Pengirim berdasarkan tipe
    public function pengirimMahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'pengirim_id', 'nim');
    }
    
    public function pengirimDosen()
    {
        return $this->belongsTo(Dosen::class, 'pengirim_id', 'nip');
    }
    
    // Helper method untuk mendapatkan pengirim
    public function getPengirimAttribute()
    {
        if ($this->tipe_pengirim == 'mahasiswa') {
            return $this->pengirimMahasiswa;
        } else if ($this->tipe_pengirim == 'dosen') {
            return $this->pengirimDosen;
        }
        return null;
    }
    
    // Method accessor untuk memastikan dibaca selalu boolean
    public function getDibacaAttribute($value)
    {
        return (bool) $value;
    }
    
    // Method mutator untuk memastikan dibaca selalu boolean
    public function setDibacaAttribute($value)
    {
        $this->attributes['dibaca'] = (bool) $value;
    }
    
    // Helper method untuk mengecek apakah ada lampiran
    public function hasAttachment()
    {
        return !empty($this->lampiran);
    }
    
    // Helper method untuk mendapatkan nama file/judul dari URL lampiran
    public function getAttachmentName()
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        
        // Coba ekstrak nama file dari URL Google Drive atau URL lainnya
        $url = $this->lampiran;
        
        // Jika Google Drive, ambil ID file
        if (strpos($url, 'drive.google.com') !== false) {
            return 'Google Drive File';
        }
        
        // Untuk URL lainnya, ambil nama file
        $path = parse_url($url, PHP_URL_PATH);
        $filename = basename($path);
        
        return $filename ?: 'Lampiran';
    }
    
    /**
     * Format tanggal untuk tampilan
     */
    public function formattedCreatedAt($format = 'H:i')
    {
        return Carbon::parse($this->created_at)
            ->timezone('Asia/Jakarta')
            ->format($format);
    }
    
    // Method untuk mengecek apakah balasan dari mahasiswa
    public function isFromMahasiswa()
    {
        return $this->tipe_pengirim === 'mahasiswa';
    }
    
    // Method untuk mengecek apakah balasan dari dosen
    public function isFromDosen()
    {
        return $this->tipe_pengirim === 'dosen';
    }
    
    // TAMBAHAN: Scope untuk filter balasan yang di-pin
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
    
    // TAMBAHAN: Scope untuk filter balasan yang tidak di-pin
    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }
    
    // TAMBAHAN: Method untuk toggle pin status
    public function togglePin()
    {
        $this->is_pinned = !$this->is_pinned;
        return $this->save();
    }
    
    // TAMBAHAN: Method untuk pin balasan
    public function pin()
    {
        $this->is_pinned = true;
        return $this->save();
    }
    
    // TAMBAHAN: Method untuk unpin balasan
    public function unpin()
    {
        $this->is_pinned = false;
        return $this->save();
    }
    
    // TAMBAHAN: Method accessor untuk is_pinned
    public function getIsPinnedAttribute($value)
    {
        return (bool) $value;
    }
    
    // TAMBAHAN: Method mutator untuk is_pinned
    public function setIsPinnedAttribute($value)
    {
        $this->attributes['is_pinned'] = (bool) $value;
    }
}