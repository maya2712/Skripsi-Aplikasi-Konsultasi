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
        'pengirim_role', // Tambahkan ini
        'nip_penerima',
        'nim_penerima',
        'penerima_role',
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
    
    /**
     * Format tanggal untuk tampilan
     */
    public function formattedCreatedAt($format = 'H:i')
    {
        return Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format($format);
    }
    
    // TAMBAHAN: Helper method untuk mengecek apakah ada lampiran
    public function hasAttachment()
    {
        return !empty($this->lampiran);
    }
    
    // TAMBAHAN: Helper method untuk mendapatkan nama file/judul dari URL lampiran
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
}