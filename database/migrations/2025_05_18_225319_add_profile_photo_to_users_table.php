<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('profile_photo');
        });
    }
};