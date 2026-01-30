<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $fillable = [
        'pendaftaran_id',
        'diagnosa',
        'tindakan',
        'resep_obat',
    ];

    public function pendaftaranKlinik() {
        return $this->belongsTo(PendaftaranKlinik::class, 'pendaftaran_id');
    }

    public function obat() {
        return $this->belongsTo(Obat::class, 'resep_obat', 'kode_obat');
    }
    
}
