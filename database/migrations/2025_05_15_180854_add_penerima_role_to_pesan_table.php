<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->string('penerima_role')->default('dosen')->after('nip_penerima');
        });
    }

    public function down()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->dropColumn('penerima_role');
        });
    }
};