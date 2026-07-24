<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paket;

class PaketKdmpSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_paket'  => 'Paket A Lengkap',
                'kategori'    => 'KDMP',
                'kecepatan'   => '50 Mbps',
                'harga'       => 7464000,
                'keterangan'  => 'Digikop, IP Cam Indoor, Cloud Recording, People Counting (AI Video Analytic)',
            ],
            [
                'nama_paket'  => 'Paket B Lengkap',
                'kategori'    => 'KDMP',
                'kecepatan'   => '50 Mbps',
                'harga'       => 7464000,
                'keterangan'  => 'Digikop, Duolink Cam Indoor, Cloud Recording, People Counting (AI Video Analytic)',
            ],
            [
                'nama_paket'  => 'Paket C Lengkap',
                'kategori'    => 'KDMP',
                'kecepatan'   => '50 Mbps',
                'harga'       => 7464000,
                'keterangan'  => 'Digikop, IP Cam Indoor, Cloud Recording, Smoke & Fire Detection (AI Video Analytic)',
            ],
            [
                'nama_paket'  => 'Paket D Lengkap',
                'kategori'    => 'KDMP',
                'kecepatan'   => '50 Mbps',
                'harga'       => 7464000,
                'keterangan'  => 'Padi Kasir (POS), Duolink Cam Indoor, Cloud Recording, Smoke & Fire Detection (AI Video Analytic)',
            ],
        ];

        foreach ($data as $row) {
            // Prevent duplicate seeding
            $exists = Paket::where('nama_paket', $row['nama_paket'])
                ->where('kategori', 'KDMP')
                ->exists();

            if (!$exists) {
                Paket::create(array_merge($row, ['aktif' => true]));
            }
        }
    }
}
