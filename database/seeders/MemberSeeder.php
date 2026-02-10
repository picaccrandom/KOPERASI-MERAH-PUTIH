<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'nik' => '3271010101890001',
                'nama_lengkap' => 'Budi Santoso',
                'nomor_hp' => '081234567890',
                'alamat' => 'Jalan Merdeka No. 123, Surabaya',
                'email' => 'budi@example.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'foto_ktp' => null,
            ],
            [
                'nik' => '3271010101890002',
                'nama_lengkap' => 'Siti Nurhaliza',
                'nomor_hp' => '082345678901',
                'alamat' => 'Jalan Ahmad Yani No. 456, Surabaya',
                'email' => 'siti@example.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'foto_ktp' => null,
            ],
            [
                'nik' => '3271010101890003',
                'nama_lengkap' => 'Ahmad Hidayat',
                'nomor_hp' => '083456789012',
                'alamat' => 'Jalan Diponegoro No. 789, Surabaya',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'foto_ktp' => null,
            ],
            [
                'nik' => '3271010101890004',
                'nama_lengkap' => 'Nur Azizah',
                'nomor_hp' => '084567890123',
                'alamat' => 'Jalan Jambi No. 321, Surabaya',
                'email' => 'nur@example.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'foto_ktp' => null,
            ],
            [
                'nik' => '3271010101890005',
                'nama_lengkap' => 'Ranto Putra',
                'nomor_hp' => '085678901234',
                'alamat' => 'Jalan Sudirman No. 654, Surabaya',
                'email' => 'ranto@example.com',
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'foto_ktp' => null,
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
