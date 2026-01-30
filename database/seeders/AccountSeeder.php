<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. UNIT KANTOR PUSAT (KAS)
        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '1101'],
            ['nama_akun' => 'Kas Koperasi', 'kategori' => 'Aset', 'saldo_awal' => 0]
        );

        // 2. UNIT SIMPAN PINJAM (LIABILITAS & ASET)
        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '2101'],
            ['nama_akun' => 'Simpanan Sukarela Anggota', 'kategori' => 'Liabilitas', 'saldo_awal' => 0]
        );

        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '1201'],
            ['nama_akun' => 'Piutang Pinjaman Anggota', 'kategori' => 'Aset', 'saldo_awal' => 0]
        );

        // 3. UNIT PENDAPATAN (REVENUE)
        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '4101'],
            ['nama_akun' => 'Pendapatan Unit Apotek', 'kategori' => 'Pendapatan', 'saldo_awal' => 0]
        );

        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '4102'],
            ['nama_akun' => 'Pendapatan Unit Klinik', 'kategori' => 'Pendapatan', 'saldo_awal' => 0]
        );

        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '4201'],
            ['nama_akun' => 'Pendapatan Bunga Pinjaman', 'kategori' => 'Pendapatan', 'saldo_awal' => 0]
        );

        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '5101'],
            ['nama_akun' => 'Beban Operasional Kantor', 'kategori' => 'Beban', 'saldo_awal' => 0]
        );
        \App\Models\Account::updateOrCreate(
            ['kode_akun' => '5102'],
            ['nama_akun' => 'Beban Gaji Karyawan', 'kategori' => 'Beban', 'saldo_awal' => 0]
        );
    }
}
