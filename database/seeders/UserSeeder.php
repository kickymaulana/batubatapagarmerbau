<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Kecamatan
        User::create([
            'name' => 'Admin Pagar Merbau',
            'email' => 'admin.kecamatan@mail.com', // Mengandung kata 'admin'
            'password' => Hash::make('password123'),
        ]);

        // 2. Akun Warga Contoh 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'password' => Hash::make('password123'),
        ]);

        // 3. Akun Warga Contoh 2
        User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@mail.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
