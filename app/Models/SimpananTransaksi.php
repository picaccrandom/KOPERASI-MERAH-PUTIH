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

    
}