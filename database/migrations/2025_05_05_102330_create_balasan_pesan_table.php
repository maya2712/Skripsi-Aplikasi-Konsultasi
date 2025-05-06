<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalasanPesanTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('balasan_pesan')) {
            Schema::create('balasan_pesan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_pesan');
                $table->string('pengirim_id'); // Bisa nim atau nip
                $table->enum('tipe_pengirim', ['mahasiswa', 'dosen']);
                $table->text('isi_balasan');
                $table->boolean('dibaca')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('balasan_pesan');
    }
}