<?php

namespace Database\Seeders;

use App\Models\PendaftaranKlinik;
use Illuminate\Database\Seeder;

class PendaftaranKlinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendaftarans = [
            [
                'no_registrasi' => 'REG001',
                'member_id' => 1,
                'keluhan' => 'Sakit kepala dan demam',
                'tensi' => '120/80',
                'biaya_daftar' => 50000,
                'status' => 'selesai',
            ],
            [
                'no_registrasi' => 'REG002',
                'member_id' => 2,
                'keluhan' => 'Batuk dan pilek',
                'tensi' => '115/75',
                'biaya_daftar' => 50000,
                'status' => 'selesai',
            ],
            [
                'no_registrasi' => 'REG003',
                'member_id' => 3,
                'keluhan' => 'Nyeri perut',
                'tensi' => '130/85',
                'biaya_daftar' => 50000,
                'status' => 'diperiksa',
            ],
            [
                'no_registrasi' => 'REG004',
                'member_id' => 4,
                'keluhan' => 'Luka di kaki',
                'tensi' => '118/78',
                'biaya_daftar' => 50000,
                'status' => 'selesai',
            ],
            [
                'no_registrasi' => 'REG005',
                'member_id' => 5,
                'keluhan' => 'Gangguan tidur',
                'tensi' => '125/82',
                'biaya_daftar' => 50000,
                'status' => 'antri',
            ],
        ];

        foreach ($pendaftarans as $pendaftaran) {
            PendaftaranKlinik::create($pendaftaran);
        }
    }
}
