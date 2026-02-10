<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            [
                'kode_obat' => 'OB001',
                'nama_obat' => 'Paracetamol 500mg',
                'kategori' => 'Analgetik',
                'stok_gudang' => 500,
                'stok_apotek' => 200,
                'min_stok' => 50,
                'harga_beli' => 2000,
                'harga_jual' => 5000,
                'satuan' => 'Tablet',
                'tgl_kadaluwarsa' => now()->addMonths(12),
            ],
            [
                'kode_obat' => 'OB002',
                'nama_obat' => 'Ibuprofen 400mg',
                'kategori' => 'Analgetik',
                'stok_gudang' => 400,
                'stok_apotek' => 150,
                'min_stok' => 50,
                'harga_beli' => 3000,
                'harga_jual' => 7000,
                'satuan' => 'Tablet',
                'tgl_kadaluwarsa' => now()->addMonths(12),
            ],
            [
                'kode_obat' => 'OB003',
                'nama_obat' => 'Sirup Batuk',
                'kategori' => 'Penyakit Pernapasan',
                'stok_gudang' => 300,
                'stok_apotek' => 100,
                'min_stok' => 30,
                'harga_beli' => 10000,
                'harga_jual' => 22000,
                'satuan' => 'Botol',
                'tgl_kadaluwarsa' => now()->addMonths(10),
            ],
            [
                'kode_obat' => 'OB004',
                'nama_obat' => 'Omeprazole 20mg',
                'kategori' => 'Pencernaan',
                'stok_gudang' => 250,
                'stok_apotek' => 80,
                'min_stok' => 30,
                'harga_beli' => 5000,
                'harga_jual' => 12000,
                'satuan' => 'Tablet',
                'tgl_kadaluwarsa' => now()->addMonths(11),
            ],
            [
                'kode_obat' => 'OB005',
                'nama_obat' => 'Salep Antibiotik',
                'kategori' => 'Topikal',
                'stok_gudang' => 150,
                'stok_apotek' => 60,
                'min_stok' => 20,
                'harga_beli' => 8000,
                'harga_jual' => 18000,
                'satuan' => 'Tube',
                'tgl_kadaluwarsa' => now()->addMonths(14),
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
