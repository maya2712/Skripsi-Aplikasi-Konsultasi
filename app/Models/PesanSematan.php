<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanSematan extends Model
{
    use HasFactory;
    
    protected $table = 'pesan_sematan';
    
    protected $fillable = [
        'nip_dosen',
        'dosen_role',
        'jenis_pesan',
        'pesan_id',
        'balasan_id',
        'isi_sematan',
        'kategori',
        'judul',
        'aktif',
        'durasi_sematan'
    ];
    
    protected $casts = [
        'aktif' => 'boolean',
        'durasi_sematan' => 'datetime',
    ];
    
    // Relasi ke dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip_dosen', 'nip');
    }
    
    // Relasi ke pesan (jika jenis_pesan = 'pesan')
    public function pesan()
    {
        return $this->belongsTo(Pesan::class, 'pesan_id');
    }
    
    // Relasi ke balasan pesan (jika jenis_pesan = 'balasan')
    public function balasan()
    {
        return $this->belongsTo(BalasanPesan::class, 'balasan_id');
    }
    
    // Scope untuk sematan yang masih aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)
                     ->where('durasi_sematan', '>', now());
    }
}