<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalasanPesan extends Model
{
    use HasFactory;

    protected $table = 'balasan_pesan';
    
    protected $fillable = [
        'id_pesan',
        'pengirim_id',
        'tipe_pengirim', // 'mahasiswa' atau 'dosen'
        'isi_balasan',
        'dibaca'
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
}