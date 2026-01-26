<?php

namespace Database\Seeders;

use Psy\Util\Str;
use App\Models\User;
use App\Models\Barang;
use App\Models\KreditAnggota;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
        Member::factory(10)->create();
        Barang::factory(10)->create();
        KreditAnggota::factory(10)->create();

        User::factory()->create([
            'name' => 'Yoga Test',
            'username' => 'yoga',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'name' => 'Neo Test',
            'username' => 'neo',
            'password' => Hash::make('password'),
        ]);
        User::factory()->create([
            'name' => 'Hendra Test',
            'username' => 'hendra',
            'password' => Hash::make('password'),
        ]);
    }
}
