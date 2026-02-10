<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRecs = [
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

        foreach ($userRecs as $userRec) {
            DB::table('userrec')->insert($userRec);
        }
    }
}
