<?php

namespace Database\Seeders;

use App\Models\Log;
use Illuminate\Database\Seeder;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logs = [
            [
                'user_id' => 1,
                'tabel' => 'transaksis',
                'aksi' => 'create',
                'data_lama' => null,
                'data_baru' => json_encode(['kode_transaksi' => 'TRX001']),
                'deskripsi' => 'Membuat transaksi baru TRX001',
            ],
            [
                'user_id' => 1,
                'tabel' => 'members',
                'aksi' => 'create',
                'data_lama' => null,
                'data_baru' => json_encode(['nama_lengkap' => 'Budi Santoso']),
                'deskripsi' => 'Membuat member baru Budi Santoso',
            ],
            [
                'user_id' => 2,
                'tabel' => 'transaksis',
                'aksi' => 'update',
                'data_lama' => json_encode(['status' => 'open']),
                'data_baru' => json_encode(['status' => 'closed']),
                'deskripsi' => 'Mengubah status transaksi TRX002 menjadi closed',
            ],
            [
                'user_id' => 2,
                'tabel' => 'obats',
                'aksi' => 'update',
                'data_lama' => json_encode(['stok_apotek' => 200]),
                'data_baru' => json_encode(['stok_apotek' => 195]),
                'deskripsi' => 'Update stok apotek obat OB001',
            ],
            [
                'user_id' => 1,
                'tabel' => 'rekam_medis',
                'aksi' => 'create',
                'data_lama' => null,
                'data_baru' => json_encode(['diagnosa' => 'Influenza berat']),
                'deskripsi' => 'Membuat rekam medis untuk pendaftaran REG001',
            ],
        ];

        foreach ($logs as $log) {
            Log::create($log);
        }
    }
}
