<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePesanTable extends Migration
{
    public function up()
    {
        Schema::create('pesan', function (Blueprint $table) {
            $table->id();
            $table->string('subjek');
            $table->string('nim_pengirim');
            $table->string('nip_penerima');
            $table->text('isi_pesan');
            $table->enum('prioritas', ['Penting', 'Umum'])->default('Umum');
            $table->enum('status', ['Aktif', 'Berakhir'])->default('Aktif');
            $table->string('lampiran')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('nim_pengirim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('nip_penerima')->references('nip')->on('dosens')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesan');
    }
}