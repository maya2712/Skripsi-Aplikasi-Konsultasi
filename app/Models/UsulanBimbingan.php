<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsulanBimbingan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nim',
        'nip',
        'event_id',
        'student_event_id',
        'mahasiswa_nama',
        'dosen_nama',
        'jenis_bimbingan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'deskripsi',    
        'status',       
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'time', // Ubah ke time karena di database time
        'waktu_selesai' => 'time', // Ubah ke time karena di database time
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_USULAN,
        'lokasi' => null,
        'keterangan' => null
    ];

    // Status bimbingan
    const STATUS_USULAN = 'USULAN';
    const STATUS_DISETUJUI = 'DISETUJUI';
    const STATUS_DITOLAK = 'DITOLAK';
    const STATUS_SELESAI = 'SELESAI';

    // Jenis bimbingan yang tersedia
    const JENIS_BIMBINGAN = [
        'skripsi' => 'Bimbingan Skripsi',
        'kp' => 'Bimbingan KP',
        'akademik' => 'Bimbingan Akademik',
        'konsultasi' => 'Konsultasi Pribadi'
    ];

    // Relasi dengan model lain
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function jadwalBimbingan(): BelongsTo
    {
        return $this->belongsTo(JadwalBimbingan::class, 'event_id', 'event_id');
    }

    // Scope query
    public function scopeUsulan($query)
    {
        return $query->where('status', self::STATUS_USULAN);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

    public function scopeByMahasiswa($query, $nim)
    {
        return $query->where('nim', $nim);
    }

    public function scopeByDosen($query, $nip)
    {
        return $query->where('nip', $nip);
    }

    // Accessor dan Mutator
    public function getWaktuLengkapAttribute(): string
    {
        return Carbon::parse($this->tanggal)->format('l, d F Y') . 
               ' | ' . 
               $this->waktu_mulai . ' - ' . $this->waktu_selesai;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_USULAN => 'warning',
            self::STATUS_DISETUJUI => 'success',
            self::STATUS_DITOLAK => 'danger',
            self::STATUS_SELESAI => 'info',
            default => 'secondary'
        };
    }

    public function getJenisBimbinganLabelAttribute(): string
    {
        return self::JENIS_BIMBINGAN[$this->jenis_bimbingan] ?? $this->jenis_bimbingan;
    }

    // Methods
    public function isDiajukan(): bool
    {
        return $this->status === self::STATUS_USULAN;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function setujui(?string $keterangan = null): bool
    {
        return $this->update([
            'status' => self::STATUS_DISETUJUI,
            'keterangan' => $keterangan
        ]);
    }

    public function tolak(string $keterangan): bool
    {
        return $this->update([
            'status' => self::STATUS_DITOLAK,
            'keterangan' => $keterangan
        ]);
    }

    public function selesaikan(?string $keterangan = null): bool
    {
        return $this->update([
            'status' => self::STATUS_SELESAI,
            'keterangan' => $keterangan
        ]);
    }

    // Untuk validasi
    public static function listStatus(): array
    {
        return [
            self::STATUS_USULAN,
            self::STATUS_DISETUJUI,
            self::STATUS_DITOLAK,
            self::STATUS_SELESAI
        ];
    }

    public static function listJenisBimbingan(): array
    {
        return array_keys(self::JENIS_BIMBINGAN);
    }

    public static function getJenisBimbinganLabel(string $key): string
    {
        return self::JENIS_BIMBINGAN[$key] ?? $key;
    }
}