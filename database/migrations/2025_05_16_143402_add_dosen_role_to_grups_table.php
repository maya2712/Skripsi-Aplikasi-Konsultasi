<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grups', function (Blueprint $table) {
            $table->string('dosen_role')->default('dosen')->after('dosen_id');
        });
    }

    public function down()
    {
        Schema::table('grups', function (Blueprint $table) {
            $table->dropColumn('dosen_role');
        });
    }
};