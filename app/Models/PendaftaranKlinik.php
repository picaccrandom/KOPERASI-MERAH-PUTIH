<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


    public function rekamMedis() {

        return $this->hasOne(RekamMedis::class, 'pendaftaran_id');
    }


}