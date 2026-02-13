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
        // Create users
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

        // Call all seeders
        $this->call([
            BarangSeeder::class,
            MemberSeeder::class,
            KreditAnggotaSeeder::class,
            AccountSeeder::class,
            // StokMasukSeeder::class,
            // TransaksiSeeder::class,
            // TransaksiDetailSeeder::class,
            // PendaftaranKlinikSeeder::class,
            // RekamMedisSeeder::class,
            // ObatSeeder::class,
            // PasienSeeder::class,
            // DistribusiBarangSeeder::class,
            // UserRecSeeder::class,
            // StokMutasiSeeder::class,
            // FakturSeeder::class,
            // JurnalSeeder::class,
            // TransaksiFaskesSeeder::class,
            // TransaksiObatDetailSeeder::class,
            // TransaksiSPSeeder::class,
            // AngsuranPeminjamanSeeder::class,
            // SimpananDetailSeeder::class,
            // BonDetailSeeder::class,
            // OrderObatSeeder::class,
            // OrderSeeder::class,
            // LogSeeder::class,
        ]);
    }
}
