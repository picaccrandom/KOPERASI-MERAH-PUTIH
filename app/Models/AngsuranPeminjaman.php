<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngsuranPeminjaman extends Model
{
    protected $table = 'angsuran_peminjamen';

    protected $fillable = [
        'no_transaksi_sp',
        'user_id',
        'angsuran_ke',
        'jumlah_angsuran',
        'total_pinjaman',
        'tanggal_bayar',
        'denda',
        'bunga',
        'tenor',
        'tanggal_pinjaman',
        'status',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Transaksi_SP::class, 'no_transaksi_SP', 'no_transaksi_SP');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
