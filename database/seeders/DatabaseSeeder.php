<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin pertama (id = 1)
        $admin1 = User::create([
            'username' => 'admin1',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('admin1'),
            'no_telpon' => '09867634',
            'tempat' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'fullname' => 'Admin Bank Sampah Desa 1',
            'role' => 1,
            'approve' => true,
            'admin_id' => null, // admin1 tidak punya atasan
        ]);

        // Admin 2–12, dengan admin_id = 1
        for ($i = 2; $i <= 12; $i++) {
            User::create([
                'username' => 'admin' . $i,
                'email' => 'admin' . $i . '@gmail.com',
                'password' => bcrypt('admin' . $i),
                'no_telpon' => '0986763' . $i,
                'tempat' => 'Jakarta',
                'tanggal_lahir' => '1990-01-0' . ($i % 9 + 1),
                'fullname' => 'Admin Bank Sampah Desa ' . $i,
                'role' => 1,
                'approve' => true,
                'admin_id' => $admin1->id, // admin pertama sebagai atasannya
            ]);
        }
    }
}