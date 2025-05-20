<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPengirimRoleToPesanTable extends Migration
{
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->string('pengirim_role')->default('dosen')->after('nip_pengirim');
        });
    }

    public function down()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->dropColumn('pengirim_role');
        });
    }
}