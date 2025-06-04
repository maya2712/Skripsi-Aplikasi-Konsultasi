<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Cek dan tambah kolom is_pinned ke tabel pesan jika belum ada
        if (!Schema::hasColumn('pesan', 'is_pinned')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->boolean('is_pinned')->default(false)->after('bookmarked');
            });
        }
        
        // Cek dan tambah kolom is_pinned ke tabel balasan_pesan jika belum ada
        if (!Schema::hasColumn('balasan_pesan', 'is_pinned')) {
            Schema::table('balasan_pesan', function (Blueprint $table) {
                $table->boolean('is_pinned')->default(false)->after('dibaca');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Hapus kolom is_pinned dari tabel pesan jika ada
        if (Schema::hasColumn('pesan', 'is_pinned')) {
            Schema::table('pesan', function (Blueprint $table) {
                $table->dropColumn('is_pinned');
            });
        }
        
        // Hapus kolom is_pinned dari tabel balasan_pesan jika ada
        if (Schema::hasColumn('balasan_pesan', 'is_pinned')) {
            Schema::table('balasan_pesan', function (Blueprint $table) {
                $table->dropColumn('is_pinned');
            });
        }
    }
};