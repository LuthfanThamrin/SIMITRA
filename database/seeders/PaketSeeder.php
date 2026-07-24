<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paket;

class PaketSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // HSI Bisnis
            ['nama_paket' => 'HSI Bisnis 1:1', 'kategori' => 'HSI Bisnis', 'kecepatan' => '50 Mbps', 'harga' => 320000],
            ['nama_paket' => 'HSI Bisnis 1:1', 'kategori' => 'HSI Bisnis', 'kecepatan' => '75 Mbps', 'harga' => 365000],
            ['nama_paket' => 'HSI Bisnis 1:1', 'kategori' => 'HSI Bisnis', 'kecepatan' => '100 Mbps', 'harga' => 440000],
            ['nama_paket' => 'HSI Bisnis 1:1', 'kategori' => 'HSI Bisnis', 'kecepatan' => '150 Mbps', 'harga' => 540000],
            ['nama_paket' => 'HSI Bisnis 1:1', 'kategori' => 'HSI Bisnis', 'kecepatan' => '300 Mbps', 'harga' => 950000],

            // HSI Basic
            ['nama_paket' => 'HSI Basic 1:2', 'kategori' => 'HSI Basic', 'kecepatan' => '50 Mbps', 'harga' => 355000],
            ['nama_paket' => 'HSI Basic 1:2', 'kategori' => 'HSI Basic', 'kecepatan' => '75 Mbps', 'harga' => 415000],
            ['nama_paket' => 'HSI Basic 1:2', 'kategori' => 'HSI Basic', 'kecepatan' => '100 Mbps', 'harga' => 535000],
            ['nama_paket' => 'HSI Basic 1:2', 'kategori' => 'HSI Basic', 'kecepatan' => '200 Mbps', 'harga' => 790000],
            ['nama_paket' => 'HSI Basic 1:2', 'kategori' => 'HSI Basic', 'kecepatan' => '300 Mbps', 'harga' => 1130000],

            // WMS Lite
            ['nama_paket' => 'WMS Lite', 'kategori' => 'WMS Lite', 'kecepatan' => '30 Mbps', 'harga' => 375000],
            ['nama_paket' => 'WMS Lite', 'kategori' => 'WMS Lite', 'kecepatan' => '40 Mbps', 'harga' => 475000],
            ['nama_paket' => 'WMS Lite', 'kategori' => 'WMS Lite', 'kecepatan' => '50 Mbps', 'harga' => 575000],
            ['nama_paket' => 'WMS Lite', 'kategori' => 'WMS Lite', 'kecepatan' => '100 Mbps', 'harga' => 1000000],

            // WMS Reguler
            ['nama_paket' => 'WMS Reguler Silver', 'kategori' => 'WMS Reguler', 'kecepatan' => '20 Mbps', 'harga' => 435000],
            ['nama_paket' => 'WMS Reguler Gold', 'kategori' => 'WMS Reguler', 'kecepatan' => '50 Mbps', 'harga' => 950000],
            ['nama_paket' => 'WMS Reguler Platinum', 'kategori' => 'WMS Reguler', 'kecepatan' => '50 Mbps', 'harga' => 1500000],
            ['nama_paket' => 'WMS Reguler Diamond', 'kategori' => 'WMS Reguler', 'kecepatan' => '200 Mbps', 'harga' => 4500000],
            ['nama_paket' => 'WMS Reguler Crown', 'kategori' => 'WMS Reguler', 'kecepatan' => '300 Mbps', 'harga' => 3050000],
        ];

        foreach ($data as $row) {
            Paket::create(array_merge($row, ['aktif' => true]));
        }
    }
}
