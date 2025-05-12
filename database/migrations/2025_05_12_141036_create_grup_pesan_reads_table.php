<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupPesanReadsTable extends Migration
{
    public function up()
    {
        Schema::create('grup_pesan_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grup_pesan_id');
            $table->string('user_id'); // Bisa berupa NIM mahasiswa atau NIP dosen
            $table->enum('user_type', ['dosen', 'mahasiswa']);
            $table->boolean('read')->default(false);
            $table->timestamps();
            
            // Foreign key ke tabel grup_pesan
            $table->foreign('grup_pesan_id')->references('id')->on('grup_pesan')->onDelete('cascade');
            // Unique constraint untuk mencegah duplikasi
            $table->unique(['grup_pesan_id', 'user_id', 'user_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grup_pesan_reads');
    }
}