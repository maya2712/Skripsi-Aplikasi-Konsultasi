<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('balasan_pesan', function (Blueprint $table) {
            $table->string('lampiran')->nullable()->after('isi_balasan');
        });
    }

    public function down()
    {
        Schema::table('balasan_pesan', function (Blueprint $table) {
            $table->dropColumn('lampiran');
        });
    }
};