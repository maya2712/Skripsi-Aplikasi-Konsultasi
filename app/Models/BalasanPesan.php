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
    
    /**
     * Format tanggal untuk tampilan
     */
    public function formattedCreatedAt($format = 'H:i')
    {
        return \Carbon\Carbon::parse($this->created_at)
        ->timezone('Asia/Jakarta')
        ->format($format);
    }
}