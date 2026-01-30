<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranKlinik extends Model
{
    protected $table = 'pendaftaran_kliniks';

    protected $fillable = [
        'no_registrasi',
        'member_id',
        'keluhan',
        'tensi',
        'biaya_daftar',
        'status'
    ];


    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function transaksiFaskes() {
        return $this->BelongsTo(TransaksiFaskes::class, 'no_registrasi', 'kode_pendaftaran')->where('COA', 'Klinik');
    }

    public function rekamMedis() {

        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }


}
