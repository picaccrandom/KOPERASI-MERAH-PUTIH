<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngsuranPeminjaman extends Model
{
    protected $table = 'angsuran_peminjamen';

    protected $fillable = [
        'kode_pinjaman',
        'angsuran_ke',
        'tanggal_pinjaman',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'jumlah_angsuran',
        'jumlah_bayar',
        'denda',
        'status',
    ];


    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'kode_pinjaman');
    }
}
