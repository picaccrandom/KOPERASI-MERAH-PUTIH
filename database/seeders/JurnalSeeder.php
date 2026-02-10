<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use Illuminate\Database\Seeder;

class JurnalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurnals = [
            [
                'tgl_transaksi' => now()->subDays(5),
                'keterangan' => 'Penerimaan Pendapatan Apotek',
                'referensi' => 'TRX001',
                'debit' => 500000,
                'kredit' => 0,
                'account_id' => 1,
            ],
            [
                'tgl_transaksi' => now()->subDays(5),
                'keterangan' => 'Pencatatan Pendapatan Apotek',
                'referensi' => 'TRX001',
                'debit' => 0,
                'kredit' => 500000,
                'account_id' => 4,
            ],
            [
                'tgl_transaksi' => now()->subDays(4),
                'keterangan' => 'Penerimaan Tunai dari Pelanggan',
                'referensi' => 'TRX002',
                'debit' => 350000,
                'kredit' => 0,
                'account_id' => 1,
            ],
            [
                'tgl_transaksi' => now()->subDays(4),
                'keterangan' => 'Pencatatan Pendapatan dari Transaksi',
                'referensi' => 'TRX002',
                'debit' => 0,
                'kredit' => 350000,
                'account_id' => 4,
            ],
            [
                'tgl_transaksi' => now()->subDays(3),
                'keterangan' => 'Pembayaran Biaya Operasional',
                'referensi' => 'BIAYA001',
                'debit' => 200000,
                'kredit' => 0,
                'account_id' => 5,
            ],
        ];

        foreach ($jurnals as $jurnal) {
            Jurnal::create($jurnal);
        }
    }
}
