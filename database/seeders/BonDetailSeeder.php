<?php

namespace Database\Seeders;

use App\Models\BonDetail;
use Illuminate\Database\Seeder;

class BonDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bons = [
            [
                'no_transaksi_sp' => 'SP001',
                'status' => 'lunas',
            ],
            [
                'no_transaksi_sp' => 'SP002',
                'status' => 'belum',
            ],
            [
                'no_transaksi_sp' => 'SP003',
                'status' => 'lunas',
            ],
            [
                'no_transaksi_sp' => 'SP004',
                'status' => 'belum',
            ],
            [
                'no_transaksi_sp' => 'SP005',
                'status' => 'belum',
            ],
        ];

        foreach ($bons as $bon) {
            BonDetail::create($bon);
        }
    }
}
