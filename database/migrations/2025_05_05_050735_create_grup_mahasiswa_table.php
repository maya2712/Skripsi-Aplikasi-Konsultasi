<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grup_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grup_id');
            $table->string('mahasiswa_nim'); // NIM mahasiswa
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('grup_id')->references('id')->on('grups')->onDelete('cascade');
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grup_mahasiswa');
    }
};