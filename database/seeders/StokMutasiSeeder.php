<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokMutasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stokMutasis = [
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($stokMutasis as $stokMutasi) {
            DB::table('stok_mutasis')->insert($stokMutasi);
        }
    }
}
