<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grup_pesan', function (Blueprint $table) {
            $table->string('sender_role')->default('dosen')->after('tipe_pengirim');
        });
    }

    public function down()
    {
        Schema::table('grup_pesan', function (Blueprint $table) {
            $table->dropColumn('sender_role');
        });
    }
};