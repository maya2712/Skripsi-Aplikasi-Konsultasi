<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupPesanTable extends Migration
{
    public function up()
    {
        Schema::create('grup_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grup_id');
            $table->string('pengirim_id'); // Bisa berupa NIP dosen atau NIM mahasiswa
            $table->enum('tipe_pengirim', ['dosen', 'mahasiswa']);
            $table->text('isi_pesan');
            $table->string('lampiran')->nullable();
            $table->timestamps();
            
            // Foreign key ke tabel grups
            $table->foreign('grup_id')->references('id')->on('grups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grup_pesan');
    }
}