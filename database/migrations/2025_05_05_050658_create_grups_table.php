<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grups', function (Blueprint $table) {
            $table->id();
            $table->string('nama_grup');
            $table->unsignedBigInteger('dosen_id'); // ID dosen yang membuat grup
            $table->timestamps();
            
            $table->foreign('dosen_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grups');
    }
};