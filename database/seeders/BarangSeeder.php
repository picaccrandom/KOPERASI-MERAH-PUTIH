<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Sabun Mandi',
                'kategori' => 'Kebutuhan Sehari-hari',
                'stok' => 150,
                'satuan' => 'pcs',
                'harga_jual' => 5000,
                'harga_beli' => 3000,
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Shampoo',
                'kategori' => 'Kebutuhan Sehari-hari',
                'stok' => 130,
                'satuan' => 'pcs',
                'harga_jual' => 15000,
                'harga_beli' => 10000,
            ],
            [
                'kode_barang' => 'BRG003',
                'nama_barang' => 'Pasta Gigi',
                'kategori' => 'Kebutuhan Sehari-hari',
                'stok' => 140,
                'satuan' => 'pcs',
                'harga_jual' => 8000,
                'harga_beli' => 5000,
            ],
            [
                'kode_barang' => 'BRG004',
                'nama_barang' => 'Vitamin C',
                'kategori' => 'Kesehatan',
                'stok' => 200,
                'satuan' => 'botol',
                'harga_jual' => 35000,
                'harga_beli' => 25000,
            ],
            [
                'kode_barang' => 'BRG005',
                'nama_barang' => 'Masker Medis',
                'kategori' => 'Kesehatan',
                'stok' => 500,
                'satuan' => 'box',
                'harga_jual' => 50000,
                'harga_beli' => 35000,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
