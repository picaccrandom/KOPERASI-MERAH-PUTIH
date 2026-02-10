<?php

namespace Database\Seeders;

use App\Models\TransaksiDetail;
use Illuminate\Database\Seeder;

class TransaksiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksiDetails = [
            [
                'kode_transaksi' => 'TRX001',
                'barang_id' => 1,
                'qty' => 2,
                'harga_satuan' => 250000,
                'subtotal' => 500000,
            ],
            [
                'kode_transaksi' => 'TRX002',
                'barang_id' => 2,
                'qty' => 1,
                'harga_satuan' => 350000,
                'subtotal' => 350000,
            ],
            [
                'kode_transaksi' => 'TRX003',
                'barang_id' => 1,
                'qty' => 3,
                'harga_satuan' => 250000,
                'subtotal' => 750000,
            ],
            [
                'kode_transaksi' => 'TRX004',
                'barang_id' => 3,
                'qty' => 2,
                'harga_satuan' => 225000,
                'subtotal' => 450000,
            ],
            [
                'kode_transaksi' => 'TRX005',
                'barang_id' => 2,
                'qty' => 1,
                'harga_satuan' => 600000,
                'subtotal' => 600000,
            ],
        ];

        foreach ($transaksiDetails as $detail) {
            TransaksiDetail::create($detail);
        }
    }
}
