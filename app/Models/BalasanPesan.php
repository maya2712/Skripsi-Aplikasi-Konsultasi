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
    
    // Relasi dengan pesan utama
    public function pesan()
    {
        return $this->belongsTo(Pesan::class, 'id_pesan');
    }
    
    // Relasi polymorphic untuk pengirim
    public function pengirim()
    {
        if ($this->tipe_pengirim == 'mahasiswa') {
            return Mahasiswa::find($this->pengirim_id);
        } else {
            return Dosen::find($this->pengirim_id);
        }
    }
}