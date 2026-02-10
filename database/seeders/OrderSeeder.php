<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'kode_order' => 'ORD001',
                'member_id' => 1,
                'items' => json_encode([
                    ['nama_obat' => 'Paracetamol 500mg', 'qty' => 2, 'harga' => 5000],
                    ['nama_obat' => 'Vitamin C', 'qty' => 1, 'harga' => 35000],
                ]),
                'total_biaya_tindakan' => 150000,
                'status' => 'processed',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'kode_order' => 'ORD002',
                'member_id' => 2,
                'items' => json_encode([
                    ['nama_obat' => 'Sirup Batuk', 'qty' => 1, 'harga' => 22000],
                ]),
                'total_biaya_tindakan' => 85000,
                'status' => 'processed',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'kode_order' => 'ORD003',
                'member_id' => 3,
                'items' => json_encode([
                    ['nama_obat' => 'Omeprazole 20mg', 'qty' => 3, 'harga' => 12000],
                    ['nama_obat' => 'Antasida', 'qty' => 1, 'harga' => 15000],
                ]),
                'total_biaya_tindakan' => 190000,
                'status' => 'pending',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'kode_order' => 'ORD004',
                'member_id' => 4,
                'items' => json_encode([
                    ['nama_obat' => 'Salep Antibiotik', 'qty' => 1, 'harga' => 18000],
                    ['nama_obat' => 'Ibuprofen 400mg', 'qty' => 2, 'harga' => 7000],
                ]),
                'total_biaya_tindakan' => 145000,
                'status' => 'processed',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'kode_order' => 'ORD005',
                'member_id' => 5,
                'items' => json_encode([
                    ['nama_obat' => 'Magnesium Supplement', 'qty' => 1, 'harga' => 25000],
                ]),
                'total_biaya_tindakan' => 75000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert($order);
        }
    }
}
