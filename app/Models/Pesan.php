<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pesan extends Model
{
    use HasFactory;
    protected $table = 'pesan';
    
    protected $fillable = [
        'subjek',
        'nim_pengirim',
        'nip_pengirim',  
        'nip_penerima',
        'nim_penerima',
        'penerima_role',
        'dosen_role', 
        'isi_pesan',
        'prioritas',
        'status',
        'lampiran',
        'dibaca'
    ];
    
    protected $casts = [
        'dibaca' => 'boolean',
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
            return $this->belongsTo(Mahasiswa::class, 'nim_penerima', 'nim')->withDefault([
                'nama' => 'Mahasiswa (Tidak Ditemukan)',
                'nim' => $this->nim_penerima
            ]);
        } 
        
        if (!empty($this->nip_penerima)) {
            return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip')->withDefault([
                'nama' => 'Dosen (Tidak Ditemukan)',
                'nip' => $this->nip_penerima,
                'jabatan' => 'Tidak Tersedia'
            ]);
        }
        
        // Fallback default relation untuk menghindari null
        return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip')->withDefault([
            'nama' => 'Tidak Ditemukan',
            'jabatan' => 'Tidak Tersedia'
        ]);
    }
    
    // Relationship khusus untuk mahasiswa pengirim dan penerima
    public function mahasiswaPengirim()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_pengirim', 'nim');
    }
    
    public function mahasiswaPenerima()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim_penerima', 'nim');
    }
    
    // Relationship khusus untuk dosen pengirim dan penerima
    public function dosenPengirim()
    {
        return $this->belongsTo(Dosen::class, 'nip_pengirim', 'nip');
    }
    
    public function dosenPenerima()
    {
        return $this->belongsTo(Dosen::class, 'nip_penerima', 'nip');
    }
    
    // Relasi untuk balasan pesan
    public function balasan()
    {
        return $this->hasMany(BalasanPesan::class, 'id_pesan');
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