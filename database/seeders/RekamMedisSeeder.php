<?php

namespace Database\Seeders;

use App\Models\RekamMedis;
use Illuminate\Database\Seeder;

class RekamMedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rekamMedis = [
            [
                'pendaftaran_id' => 1,
                'diagnosa' => 'Influenza berat',
                'tindakan' => 'Istirahat, minum obat pereda demam',
                'resep_obat' => 'Paracetamol 500mg 3x1, Ibuprofen 400mg 2x1',
            ],
            [
                'pendaftaran_id' => 2,
                'diagnosa' => 'Batuk pilek biasa',
                'tindakan' => 'Istirahat dan minum banyak air',
                'resep_obat' => 'Sirup batuk 3x1 sendok, Permen pelega tenggorokan',
            ],
            [
                'pendaftaran_id' => 3,
                'diagnosa' => 'Gastritis akut',
                'tindakan' => 'Diet makanan berserat, hindari makanan pedas',
                'resep_obat' => 'Antasida 2x1, Omeprazole 1x1 sebelum makan pagi',
            ],
            [
                'pendaftaran_id' => 4,
                'diagnosa' => 'Luka terbuka sedang',
                'tindakan' => 'Pembersihan luka, antiseptik, perban steril',
                'resep_obat' => 'Salep antibiotik 3x1, Ibuprofen 400mg 3x1',
            ],
            [
                'pendaftaran_id' => 5,
                'diagnosa' => 'Insomnia ringan',
                'tindakan' => 'Edukasi pola tidur, relaksasi sebelum tidur',
                'resep_obat' => 'Magnesium supplement 1x1 malam sebelum tidur',
            ],
        ];

        foreach ($rekamMedis as $rekam) {
            RekamMedis::create($rekam);
        }
    }
}
