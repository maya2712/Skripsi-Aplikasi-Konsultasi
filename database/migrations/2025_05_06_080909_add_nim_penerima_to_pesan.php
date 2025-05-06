<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNimPenerimaToPesan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Tambahkan kolom nim_penerima
            $table->string('nim_penerima')->nullable()->after('nip_pengirim');
            
            // Ubah nip_penerima menjadi nullable juga
            $table->string('nip_penerima')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn('nim_penerima');
            
            // Kembalikan nip_penerima menjadi tidak nullable
            $table->string('nip_penerima')->nullable(false)->change();
        });
    }
}