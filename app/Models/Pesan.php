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
        'nip_penerima',
        'isi_pesan',
        'prioritas',
        'status',
        'lampiran',
        'dibaca'
    ];
    
    // Relasi dengan pengirim (mahasiswa)
    public function pengirim()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_pengirim', 'nim');
    }
    
    // Relasi dengan penerima (dosen)
    public function penerima()
    {
        return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip');
    }

    // Relasi untuk balasan pesan
    public function balasan()
    {
        return $this->hasMany(BalasanPesan::class, 'id_pesan');
    }
}