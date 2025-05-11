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
        'dibaca'
    ];
    
    // PERBAIKAN: Pastikan cast untuk field dibaca bekerja dengan benar
    protected $casts = [
        'dibaca' => 'boolean',
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
    
    // PENAMBAHAN: Method accessor untuk memastikan dibaca selalu boolean
    public function getDibacaAttribute($value)
    {
        return (bool) $value;
    }
    
    // PENAMBAHAN: Method mutator untuk memastikan dibaca selalu boolean
    public function setDibacaAttribute($value)
    {
        $this->attributes['dibaca'] = (bool) $value;
    }
    
    // Sebelum menyimpan ke database, konversi ke UTC
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->setTimezone('UTC');
    }
    
    // Saat mengambil dari database, konversi ke waktu lokal
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta');
    }
    
    // Juga untuk updated_at
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = Carbon::parse($value)->setTimezone('UTC');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta');
    }
}