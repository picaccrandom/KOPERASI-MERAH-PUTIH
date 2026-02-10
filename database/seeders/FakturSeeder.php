<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakturs = [
            [
                'no_faktur' => 'FAK001',
                'tujuan' => 'Desa Mertapura',
                'driver' => 'Supardi',
                'nopol_truk' => 'L 1234 AB',
                'tgl_kirim' => now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_faktur' => 'FAK002',
                'tujuan' => 'Pasar Jatinegara',
                'driver' => 'Budi Rahmat',
                'nopol_truk' => 'L 5678 CD',
                'tgl_kirim' => now()->subDays(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_faktur' => 'FAK003',
                'tujuan' => 'Desa Banjarejo',
                'driver' => 'Ahmad Suryanto',
                'nopol_truk' => 'L 9012 EF',
                'tgl_kirim' => now()->subDays(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_faktur' => 'FAK004',
                'tujuan' => 'Pasar Minggu',
                'driver' => 'Slamet',
                'nopol_truk' => 'L 3456 GH',
                'tgl_kirim' => now()->subDays(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'no_faktur' => 'FAK005',
                'tujuan' => 'Desa Suryakencana',
                'driver' => 'Hendra Prasetya',
                'nopol_truk' => 'L 7890 IJ',
                'tgl_kirim' => now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($fakturs as $faktur) {
            DB::table('fakturs')->insert($faktur);
        }
    }
}
