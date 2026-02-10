<?php

namespace Database\Seeders;

use App\Models\AngsuranPeminjaman;
use Illuminate\Database\Seeder;

class AngsuranPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $angsurans = [
            [
                'no_transaksi_sp' => 'SP001',
                'user_id' => 1,
                'angsuran_ke' => 1,
                'batas_bayar' => now()->addMonths(1),
                'tanggal_bayar' => now()->addDays(15),
                'jumlah_angsuran' => 100000,
                'total_pinjaman' => 1000000,
                'denda' => 0,
                'bunga' => 2,
                'tenor' => 12,
                'tanggal_pinjaman' => now()->subDays(10),
                'status' => 'lunas',
            ],
            [
                'no_transaksi_sp' => 'SP001',
                'user_id' => 1,
                'angsuran_ke' => 2,
                'batas_bayar' => now()->addMonths(2),
                'tanggal_bayar' => null,
                'jumlah_angsuran' => 100000,
                'total_pinjaman' => 1000000,
                'denda' => 0,
                'bunga' => 2,
                'tenor' => 12,
                'tanggal_pinjaman' => now()->subDays(10),
                'status' => 'belum',
            ],
            [
                'no_transaksi_sp' => 'SP002',
                'user_id' => 1,
                'angsuran_ke' => 1,
                'batas_bayar' => now()->addMonths(1),
                'tanggal_bayar' => now(),
                'jumlah_angsuran' => 150000,
                'total_pinjaman' => 1500000,
                'denda' => 0,
                'bunga' => 2,
                'tenor' => 10,
                'tanggal_pinjaman' => now()->subDays(9),
                'status' => 'lunas',
            ],
            [
                'no_transaksi_sp' => 'SP003',
                'user_id' => 2,
                'angsuran_ke' => 1,
                'batas_bayar' => now()->addMonths(1),
                'tanggal_bayar' => null,
                'jumlah_angsuran' => 200000,
                'total_pinjaman' => 2000000,
                'denda' => 0,
                'bunga' => 1.5,
                'tenor' => 12,
                'tanggal_pinjaman' => now()->subDays(8),
                'status' => 'belum',
            ],
            [
                'no_transaksi_sp' => 'SP004',
                'user_id' => 2,
                'angsuran_ke' => 1,
                'batas_bayar' => now()->addMonths(1),
                'tanggal_bayar' => null,
                'jumlah_angsuran' => 75000,
                'total_pinjaman' => 750000,
                'denda' => 0,
                'bunga' => 2,
                'tenor' => 10,
                'tanggal_pinjaman' => now()->subDays(7),
                'status' => 'belum',
            ],
        ];

        foreach ($angsurans as $angsuran) {
            AngsuranPeminjaman::create($angsuran);
        }
    }
}
