<?php

namespace Database\Seeders;

use App\Models\KreditAnggota;
use Illuminate\Database\Seeder;

class KreditAnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kreditAnggota = [
            [
                'member_id' => 1,
                'limit' => 5000000,
            ],
            [
                'member_id' => 2,
                'limit' => 3000000,
            ],
            [
                'member_id' => 3,
                'limit' => 7000000,
            ],
            [
                'member_id' => 4,
                'limit' => 4000000,
            ],
            [
                'member_id' => 5,
                'limit' => 6000000,
            ],
        ];

        foreach ($kreditAnggota as $item) {
            KreditAnggota::create($item);
        }
    }
}
