<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanSematanTable extends Migration
{
    public function up()
    {
        Schema::create('pesan_sematan', function (Blueprint $table) {
            $table->id();
            $table->string('nip_dosen');
            $table->string('jenis_pesan'); // 'pesan' atau 'balasan'
            $table->unsignedBigInteger('pesan_id')->nullable(); // ID dari pesan utama jika jenis_pesan = 'pesan'
            $table->unsignedBigInteger('balasan_id')->nullable(); // ID dari balasan jika jenis_pesan = 'balasan'
            $table->text('isi_sematan'); // Isi pesan yang disematkan
            $table->string('kategori'); // KRS, KP, Skripsi, MBKM
            $table->string('judul'); // Judul untuk FAQ
            $table->boolean('aktif')->default(true);
            $table->datetime('durasi_sematan'); // Kapan sematan berakhir
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('nip_dosen')->references('nip')->on('dosens')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesan_sematan');
    }
}