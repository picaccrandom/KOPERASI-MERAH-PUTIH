<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = [
            [
                'kode_transaksi' => 'TRX001',
                'kategori' => 'member',
                'member_id' => 1,
                'user_id' => 1,
                'tgl_transaksi' => now('Asia/Jakarta')->subDays(5),
                'grand_total' => 500000,
                'tipe_pembayaran' => 'tunai',
                'status' => 'closed',
                'total_bon' => 0,
                'total_tunai' => 500000,
            ],
            [
                'kode_transaksi' => 'TRX002',
                'kategori' => 'member',
                'member_id' => 2,
                'user_id' => 1,
                'tgl_transaksi' => now('Asia/Jakarta')->subDays(4),
                'grand_total' => 350000,
                'tipe_pembayaran' => 'tunai',
                'status' => 'closed',
                'total_bon' => 0,
                'total_tunai' => 350000,
            ],
            [
                'kode_transaksi' => 'TRX003',
                'kategori' => 'member',
                'member_id' => 3,
                'user_id' => 2,
                'tgl_transaksi' => now('Asia/Jakarta')->subDays(3),
                'grand_total' => 750000,
                'tipe_pembayaran' => 'bon',
                'status' => 'closed',
                'total_bon' => 750000,
                'total_tunai' => 0,
            ],
            [
                'kode_transaksi' => 'TRX004',
                'kategori' => 'reguler',
                'member_id' => null,
                'user_id' => 2,
                'tgl_transaksi' => now('Asia/Jakarta')->subDays(2),
                'grand_total' => 450000,
                'tipe_pembayaran' => 'tunai',
                'status' => 'closed',
                'total_bon' => 0,
                'total_tunai' => 450000,
            ],
            [
                'kode_transaksi' => 'TRX005',
                'kategori' => 'member',
                'member_id' => 4,
                'user_id' => 1,
                'tgl_transaksi' => now('Asia/Jakarta')->subDays(1),
                'grand_total' => 600000,
                'tipe_pembayaran' => 'tunai',
                'status' => 'closed',
                'total_bon' => 0,
                'total_tunai' => 600000,
            ],
        ];

        foreach ($transaksis as $transaksi) {
            Transaksi::create($transaksi);
        }
    }
}
