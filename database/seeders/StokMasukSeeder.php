<?php

namespace Database\Seeders;

use App\Models\StokMasuk;
use Illuminate\Database\Seeder;

class StokMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stokMasuks = [
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

        foreach ($stokMasuks as $stokMasuk) {
            StokMasuk::create($stokMasuk);
        }
    }
}
