<?php

namespace Database\Seeders;

use App\Models\SimpananDetail;
use Illuminate\Database\Seeder;

class SimpananDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $simpanans = [
            [
                'no_transaksi_sp' => 'SP001',
                'tanggal' => now()->subDays(10),
                'saldo' => 500000,
                'biaya_admin' => 5000,
                'jenis' => 'pokok',
                'status' => 'aktif',
            ],
            [
                'no_transaksi_sp' => 'SP002',
                'tanggal' => now()->subDays(9),
                'saldo' => 750000,
                'biaya_admin' => 7500,
                'jenis' => 'wajib',
                'status' => 'aktif',
            ],
            [
                'no_transaksi_sp' => 'SP003',
                'tanggal' => now()->subDays(8),
                'saldo' => 1000000,
                'biaya_admin' => 10000,
                'jenis' => 'pokok',
                'status' => 'aktif',
            ],
            [
                'no_transaksi_sp' => 'SP004',
                'tanggal' => now()->subDays(7),
                'saldo' => 300000,
                'biaya_admin' => 3000,
                'jenis' => 'sukarela',
                'status' => 'aktif',
            ],
            [
                'no_transaksi_sp' => 'SP005',
                'tanggal' => now()->subDays(6),
                'saldo' => 1500000,
                'biaya_admin' => 15000,
                'jenis' => 'pokok',
                'status' => 'aktif',
            ],
        ];

        foreach ($simpanans as $simpanan) {
            SimpananDetail::create($simpanan);
        }
    }
}
