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
        // Untuk tabel mahasiswas
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('email');
            }
        });

        // Untuk tabel dosens
        Schema::table('dosens', function (Blueprint $table) {
            if (!Schema::hasColumn('dosens', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('email');
            }
        });

        // Untuk tabel admins
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
        });

        Schema::table('dosens', function (Blueprint $table) {
            if (Schema::hasColumn('dosens', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
        });

        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'profile_photo')) {
                $table->dropColumn('profile_photo');
            }
        });
    }
};