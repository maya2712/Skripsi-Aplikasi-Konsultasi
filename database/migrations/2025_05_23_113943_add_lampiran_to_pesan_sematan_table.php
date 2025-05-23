<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesan_sematan', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('isi_sematan');
        });
    }

    public function down()
    {
        Schema::table('pesan_sematan', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
    }
};