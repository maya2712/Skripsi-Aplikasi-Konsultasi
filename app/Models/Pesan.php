<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $table = 'pesan';
    
    protected $fillable = [
        'subjek',
        'nim_pengirim',
        'nip_pengirim',
        'nim_penerima',
        'nip_penerima',
        'isi_pesan',
        'prioritas',
        'status',
        'lampiran',
        'dibaca'
    ];
    
    // Helper method untuk mendapatkan pengirim (baik mahasiswa maupun dosen)
    public function pengirim()
    {
        if (!empty($this->nim_pengirim)) {
            return $this->belongsTo(Mahasiswa::class, 'nim_pengirim', 'nim');
        } elseif (!empty($this->nip_pengirim)) {
            return $this->belongsTo(Dosen::class, 'nip_pengirim', 'nip');
        }
        return null;
    }
    
   // Helper method untuk mendapatkan penerima (baik mahasiswa maupun dosen)
    public function penerima()
    {
        if (!empty($this->nim_penerima)) {
            return $this->belongsTo(Mahasiswa::class, 'nim_penerima', 'nim');
        } 
        
        if (!empty($this->nip_penerima)) {
            return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip');
        }
        
        // Tambahkan fallback default relation untuk menghindari null
        return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip')->withDefault([
            'nama' => 'Tidak Ditemukan',
            'jabatan' => 'Tidak Tersedia'
        ]);
    }

    // Relasi untuk balasan pesan
    public function balasan()
    {
        return $this->hasMany(BalasanPesan::class, 'id_pesan');
    }
}