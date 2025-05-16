<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesan_sematan', function (Blueprint $table) {
            $table->string('dosen_role')->default('dosen')->after('nip_dosen');
        });
    }

    public function down()
    {
        Schema::table('pesan_sematan', function (Blueprint $table) {
            $table->dropColumn('dosen_role');
        });
    }
};