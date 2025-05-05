<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type;

return new class extends Migration
{
    public function up()
    {
        // Jika tabel grups sudah ada, hapus dulu
        if (Schema::hasTable('grups')) {
            Schema::dropIfExists('grup_mahasiswa');
            Schema::dropIfExists('grups');
        }
        
        // Buat tabel grups dengan struktur yang benar
        Schema::create('grups', function (Blueprint $table) {
            $table->id();
            $table->string('nama_grup');
            $table->string('dosen_id'); // String untuk menyimpan NIP dosen
            $table->timestamps();
            
            // Buat foreign key ke tabel dosens dengan kolom nip
            $table->foreign('dosen_id')->references('nip')->on('dosens')->onDelete('cascade');
        });
        
        // Buat tabel pivot grup_mahasiswa
        Schema::create('grup_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grup_id');
            $table->string('mahasiswa_nim');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('grup_id')->references('id')->on('grups')->onDelete('cascade');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grup_mahasiswa');
        Schema::dropIfExists('grups');
    }
};