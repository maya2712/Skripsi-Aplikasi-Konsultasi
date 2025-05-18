<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah sudah ada admin dengan email ini
        $existingAdmin = DB::table('admins')
            ->where('email', 'sherly.ratna0670@student.unri.ac.id')
            ->first();
            
        if ($existingAdmin) {
            // Jika sudah ada, update password saja
            DB::table('admins')
                ->where('id', $existingAdmin->id)
                ->update([
                    'password' => Hash::make('admin123'),
                    'updated_at' => Carbon::now()
                ]);
                
            echo "Admin sudah ada, password diperbarui.\n";
        } else {
            // Jika belum ada, cari ID terakhir
            $lastId = DB::table('admins')->max('id') ?? 0;
            
            // Tambahkan admin baru dengan ID yang lebih tinggi
            $admin = [
                'id' => $lastId + 1,
                'name' => 'Administrator',
                'email' => 'sherly.ratna0670@student.unri.ac.id',
                'password' => Hash::make('admin123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            DB::table('admins')->insert($admin);
            echo "Admin baru ditambahkan dengan ID " . ($lastId + 1) . ".\n";
        }
    }
}