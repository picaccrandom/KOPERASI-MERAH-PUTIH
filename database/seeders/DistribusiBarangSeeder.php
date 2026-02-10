<?php

namespace Database\Seeders;

use App\Models\DistribusiBarang;
use Illuminate\Database\Seeder;

class DistribusiBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distribusi = [
            [
                'kode_barang' => 'DB001',
                'nama_barang' => 'Beras Premium Grade A',
                'stok_pusat' => 500,
                'satuan_besar' => 'KARUNG',
                'harga_per_satuan' => 250000,
            ],
            [
                'kode_barang' => 'DB002',
                'nama_barang' => 'Gula Pasir Halus',
                'stok_pusat' => 300,
                'satuan_besar' => 'KARUNG',
                'harga_per_satuan' => 175000,
            ],
            [
                'kode_barang' => 'DB003',
                'nama_barang' => 'Minyak Goreng Kelapa',
                'stok_pusat' => 200,
                'satuan_besar' => 'JERIGEN',
                'harga_per_satuan' => 145000,
            ],
            [
                'kode_barang' => 'DB004',
                'nama_barang' => 'Tepung Terigu Pro',
                'stok_pusat' => 250,
                'satuan_besar' => 'KARUNG',
                'harga_per_satuan' => 120000,
            ],
            [
                'kode_barang' => 'DB005',
                'nama_barang' => 'Garam Himalaya',
                'stok_pusat' => 150,
                'satuan_besar' => 'KARUNG',
                'harga_per_satuan' => 80000,
            ],
        ];

        foreach ($distribusi as $item) {
            DistribusiBarang::create($item);
        }
    }
}
