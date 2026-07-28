<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@simitra.com'],
            [
                'nama' => 'Admin SIMITRA',
                'password' => \Illuminate\Support\Facades\Hash::make('AyamGoyeng'),
                'role' => 'admin',
                'status_aktif' => true,
                'status_pendaftaran' => 'disetujui',
            ]
        );
    }
}
