<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpananDetail extends Model
{
    protected $table = 'simpanan_details';

    protected $fillable = [
        'no_transaksi_sp', 'tanggal', 'saldo', 'biaya_admin', 'jenis', 'status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi_SP::class, 'no_transaksi_sp', 'no_transaksi_sp'); 
    }
}