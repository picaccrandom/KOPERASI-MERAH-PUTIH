<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_barang' => strtoupper(fake()->bothify('BRG-#####')),
            'nama_barang' => fake()->word(),
            'kategori' => fake()->randomElement(['Elektronik', 'Pakaian', 'Makanan', 'Minuman', 'Alat Tulis']),
            'stok' => fake()->numberBetween(0, 100),
            'satuan' => fake()->randomElement(['pcs', 'box', 'kg', 'liter']),
            'harga_beli' => fake()->randomFloat(2, 1000, 100000),
            'harga_jual' => fake()->randomFloat(2, 1500, 150000),
        ];
    }
}
