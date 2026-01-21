<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpananTransaksi extends Model
{
    protected $table = 'simpanan_transaksis';

    protected $fillable = [
        'kode_simpanan',
        'tanggal',
        'jenis',
        'tipe',
        'nominal',
        'catatan'
    ];

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class , 'kode_simpanan', 'kode_simpanan');
    }
}