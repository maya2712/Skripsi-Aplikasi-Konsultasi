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
        // Update kolom profile_photo untuk bisa menampung URL yang panjang
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('profile_photo', 500)->nullable()->change();
        });
        
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('profile_photo', 500)->nullable()->change();
        });
        
        Schema::table('admins', function (Blueprint $table) {
            $table->string('profile_photo', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('profile_photo', 255)->nullable()->change();
        });
        
        Schema::table('dosens', function (Blueprint $table) {
            $table->string('profile_photo', 255)->nullable()->change();
        });
        
        Schema::table('admins', function (Blueprint $table) {
            $table->string('profile_photo', 255)->nullable()->change();
        });
    }
};