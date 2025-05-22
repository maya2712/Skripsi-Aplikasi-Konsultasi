<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus foreign key constraint dulu
            $table->dropForeign(['konsentrasi_id']);
            
            // Ubah kolom menjadi nullable
            $table->unsignedBigInteger('konsentrasi_id')->nullable()->change();
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('konsentrasi_id')->references('id')->on('konsentrasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['konsentrasi_id']);
            
            // Ubah kolom menjadi not nullable
            $table->unsignedBigInteger('konsentrasi_id')->nullable(false)->change();
            
            // Tambahkan kembali foreign key constraint
            $table->foreign('konsentrasi_id')->references('id')->on('konsentrasi');
        });
    }
};