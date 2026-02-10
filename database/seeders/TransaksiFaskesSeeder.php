<?php

namespace Database\Seeders;

use App\Models\TransaksiFaskes;
use Illuminate\Database\Seeder;

class TransaksiFaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transaksis = [
            [
                'kode_transaksi' => 'TFAS001',
                'tanggal' => now()->subDays(5),
                'member_id' => 1,
                'user_id' => 1,
                'nama' => 'Budi Santoso',
                'COA' => '1101',
                'status' => 'closed',
                'Debit/Credit' => 'Debit',
                'Nominal' => 50000,
                'Keterangan' => 'Pendaftaran Klinik',
                'kode_pendaftaran' => 'REG001',
            ],
            [
                'kode_transaksi' => 'TFAS002',
                'tanggal' => now()->subDays(4),
                'member_id' => 2,
                'user_id' => 1,
                'nama' => 'Siti Nurhaliza',
                'COA' => '1101',
                'status' => 'closed',
                'Debit/Credit' => 'Debit',
                'Nominal' => 50000,
                'Keterangan' => 'Pendaftaran Klinik',
                'kode_pendaftaran' => 'REG002',
            ],
            [
                'kode_transaksi' => 'TFAS003',
                'tanggal' => now()->subDays(3),
                'member_id' => 3,
                'user_id' => 2,
                'nama' => 'Ahmad Hidayat',
                'COA' => '1101',
                'status' => 'open',
                'Debit/Credit' => 'Debit',
                'Nominal' => 50000,
                'Keterangan' => 'Pendaftaran Klinik',
                'kode_pendaftaran' => 'REG003',
            ],
            [
                'kode_transaksi' => 'TFAS004',
                'tanggal' => now()->subDays(2),
                'member_id' => 4,
                'user_id' => 2,
                'nama' => 'Nur Azizah',
                'COA' => '1101',
                'status' => 'closed',
                'Debit/Credit' => 'Debit',
                'Nominal' => 50000,
                'Keterangan' => 'Pendaftaran Klinik',
                'kode_pendaftaran' => 'REG004',
            ],
            [
                'kode_transaksi' => 'TFAS005',
                'tanggal' => now()->subDays(1),
                'member_id' => 5,
                'user_id' => 1,
                'nama' => 'Ranto Putra',
                'COA' => '1101',
                'status' => 'open',
                'Debit/Credit' => 'Debit',
                'Nominal' => 50000,
                'Keterangan' => 'Pendaftaran Klinik',
                'kode_pendaftaran' => 'REG005',
            ],
        ];

        foreach ($transaksis as $transaksi) {
            TransaksiFaskes::create($transaksi);
        }
    }
}
