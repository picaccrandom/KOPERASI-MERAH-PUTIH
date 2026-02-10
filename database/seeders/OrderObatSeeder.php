<?php

namespace Database\Seeders;

use App\Models\OrderObat;
use Illuminate\Database\Seeder;

class OrderObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'pendaftaran_klinik_id' => 1,
                'tanggal_order' => now()->subDays(5),
                'member_id' => 1,
                'user_id' => 1,
                'order_body' => json_encode([
                    ['obat_id' => 1, 'qty' => 2],
                    ['obat_id' => 3, 'qty' => 1],
                ]),
                'nominal' => 32000,
                'status' => 'selesai',
            ],
            [
                'pendaftaran_klinik_id' => 2,
                'tanggal_order' => now()->subDays(4),
                'member_id' => 2,
                'user_id' => 1,
                'order_body' => json_encode([
                    ['obat_id' => 3, 'qty' => 1],
                    ['obat_id' => 2, 'qty' => 1],
                ]),
                'nominal' => 29000,
                'status' => 'selesai',
            ],
            [
                'pendaftaran_klinik_id' => 3,
                'tanggal_order' => now()->subDays(3),
                'member_id' => 3,
                'user_id' => 2,
                'order_body' => json_encode([
                    ['obat_id' => 4, 'qty' => 3],
                ]),
                'nominal' => 36000,
                'status' => 'belum',
            ],
            [
                'pendaftaran_klinik_id' => 4,
                'tanggal_order' => now()->subDays(2),
                'member_id' => 4,
                'user_id' => 2,
                'order_body' => json_encode([
                    ['obat_id' => 5, 'qty' => 1],
                    ['obat_id' => 1, 'qty' => 1],
                ]),
                'nominal' => 23000,
                'status' => 'selesai',
            ],
            [
                'pendaftaran_klinik_id' => 5,
                'tanggal_order' => now()->subDays(1),
                'member_id' => 5,
                'user_id' => 1,
                'order_body' => json_encode([
                    ['obat_id' => 2, 'qty' => 2],
                ]),
                'nominal' => 14000,
                'status' => 'belum',
            ],
        ];

        foreach ($orders as $order) {
            OrderObat::create($order);
        }
    }
}
