<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    use HasFactory;
    
    protected $table = 'grups';
    
    protected $fillable = [
        'nama_grup',
        'dosen_id'
    ];
    
    // Relasi dengan dosen (pembuat grup)
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'nip');
    }
    
    // Relasi dengan mahasiswa
    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'grup_mahasiswa', 'grup_id', 'mahasiswa_nim', 'id', 'nim')
                    ->withTimestamps();
    }
}