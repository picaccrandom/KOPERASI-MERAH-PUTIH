<?php

namespace Database\Seeders;

use App\Models\TransaksiObatDetail;
use Illuminate\Database\Seeder;

class TransaksiObatDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $details = [
            [
                'kode_transaksi' => 'TFAS001',
                'obat_id' => 1,
                'nama_obat' => 'Paracetamol 500mg',
                'qty' => 2,
                'subtotal' => 10000,
            ],
            [
                'kode_transaksi' => 'TFAS002',
                'obat_id' => 3,
                'nama_obat' => 'Sirup Batuk',
                'qty' => 1,
                'subtotal' => 22000,
            ],
            [
                'kode_transaksi' => 'TFAS003',
                'obat_id' => 4,
                'nama_obat' => 'Omeprazole 20mg',
                'qty' => 3,
                'subtotal' => 36000,
            ],
            [
                'kode_transaksi' => 'TFAS004',
                'obat_id' => 5,
                'nama_obat' => 'Salep Antibiotik',
                'qty' => 1,
                'subtotal' => 18000,
            ],
            [
                'kode_transaksi' => 'TFAS005',
                'obat_id' => 2,
                'nama_obat' => 'Ibuprofen 400mg',
                'qty' => 2,
                'subtotal' => 14000,
            ],
        ];

        foreach ($details as $detail) {
            TransaksiObatDetail::create($detail);
        }
    }
}
