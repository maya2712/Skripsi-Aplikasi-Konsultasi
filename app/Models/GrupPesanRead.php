<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupPesanRead extends Model
{
    use HasFactory;
    
    protected $table = 'grup_pesan_reads';
    
    protected $fillable = [
        'grup_pesan_id',
        'user_id',
        'user_type',
        'read'
    ];
    
    protected $casts = [
        'read' => 'boolean',
    ];
    
    // Relasi ke pesan grup
    public function grupPesan()
    {
        return $this->belongsTo(GrupPesan::class, 'grup_pesan_id');
    }
}