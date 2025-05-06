<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNipPengirimToPesan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Tambahkan kolom nip_pengirim setelah kolom nim_pengirim
            // dan buat nullable karena bisa dosen atau mahasiswa yang mengirim
            $table->string('nip_pengirim')->nullable()->after('nim_pengirim');
            
            // Ubah nim_pengirim menjadi nullable juga, karena bisa dosen yang mengirim
            $table->string('nim_pengirim')->nullable()->change();
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
            $table->dropColumn('nip_pengirim');
            
            // Kembalikan nim_pengirim menjadi tidak nullable
            $table->string('nim_pengirim')->nullable(false)->change();
        });
    }
}