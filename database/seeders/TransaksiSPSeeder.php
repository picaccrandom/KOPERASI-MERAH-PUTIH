<?php

namespace Database\Seeders;

use App\Models\Transaksi_SP;
use Illuminate\Database\Seeder;

class TransaksiSPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = [
            [
                'no_transaksi_sp' => 'SP001',
                'tanggal' => now()->subDays(10),
                'member_id' => 1,
                'nama' => 'Budi Santoso',
                'COA' => '1101',
                'Debit/Credit' => 'Credit',
                'Nominal' => 1000000,
                'Keterangan' => 'Pinjaman untuk usaha',
            ],
            [
                'no_transaksi_sp' => 'SP002',
                'tanggal' => now()->subDays(9),
                'member_id' => 2,
                'nama' => 'Siti Nurhaliza',
                'COA' => '1101',
                'Debit/Credit' => 'Credit',
                'Nominal' => 1500000,
                'Keterangan' => 'Pinjaman untuk modal dagang',
            ],
            [
                'no_transaksi_sp' => 'SP003',
                'tanggal' => now()->subDays(8),
                'member_id' => 3,
                'nama' => 'Ahmad Hidayat',
                'COA' => '1101',
                'Debit/Credit' => 'Credit',
                'Nominal' => 2000000,
                'Keterangan' => 'Pinjaman untuk pendidikan',
            ],
            [
                'no_transaksi_sp' => 'SP004',
                'tanggal' => now()->subDays(7),
                'member_id' => 4,
                'nama' => 'Nur Azizah',
                'COA' => '1101',
                'Debit/Credit' => 'Credit',
                'Nominal' => 750000,
                'Keterangan' => 'Pinjaman untuk biaya kesehatan',
            ],
            [
                'no_transaksi_sp' => 'SP005',
                'tanggal' => now()->subDays(6),
                'member_id' => 5,
                'nama' => 'Ranto Putra',
                'COA' => '1101',
                'Debit/Credit' => 'Credit',
                'Nominal' => 3000000,
                'Keterangan' => 'Pinjaman untuk perumahan',
            ],
        ];

        foreach ($transaksis as $transaksi) {
            Transaksi_SP::create($transaksi);
        }
    }
}
