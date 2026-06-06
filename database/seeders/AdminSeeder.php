<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@simines.com'],
            [
                'nama' => 'Administrator SIMINES',
                'no_hp' => '081234567890',
                'alamat' => 'Kantor Pusat SIMINES, Waingapu',
                'password' => 'admin123',
                'peran' => 'admin',
            ]
        );
    }
}
