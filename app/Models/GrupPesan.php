<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GrupPesan extends Model
{
    use HasFactory;
    
    protected $table = 'grup_pesan';
    
    protected $fillable = [
        'grup_id',
        'pengirim_id',
        'tipe_pengirim',
        'isi_pesan',
        'lampiran'
    ];
    
    // Relasi ke grup
    public function grup()
    {
        return $this->belongsTo(Grup::class, 'grup_id');
    }
    
    // Relasi ke pengirim (dosen atau mahasiswa)
    public function pengirimDosen()
    {
        return $this->belongsTo(Dosen::class, 'pengirim_id', 'nip');
    }
    
    public function pengirimMahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'pengirim_id', 'nim');
    }
    
    // Relasi ke status membaca pesan
    public function readStatus()
    {
        return $this->hasMany(GrupPesanRead::class, 'grup_pesan_id');
    }
    
    // Accessor untuk waktu
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta');
    }
    
    // Method untuk mendapatkan data pengirim (bisa dosen atau mahasiswa)
    public function getPengirimAttribute()
    {
        if ($this->tipe_pengirim == 'dosen') {
            return $this->pengirimDosen;
        } else {
            return $this->pengirimMahasiswa;
        }
    }
}