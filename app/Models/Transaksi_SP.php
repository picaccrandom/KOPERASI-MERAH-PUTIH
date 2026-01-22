<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi_SP extends Model
{
    protected $table = 'transaksi__s_p_s';

    protected $fillable = [
        'no_transaksi_sp',
        'tanggal',
        'member_id',
        'nama',
        'COA',
        'Debit/Credit',
        'Nominal',
        'Keterangan',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function angsuranPeminjamans()
    {
        return $this->hasMany(AngsuranPeminjaman::class, 'no_transaksi_sp', 'no_transaksi_sp');
    }

    public function simpananDetails()
    {
        return $this->hasMany(SimpananDetail::class, 'no_transaksi_sp', 'no_transaksi_sp');
    }

    public function angsuranBelum()
    {
        return $this->hasOne(AngsuranPeminjaman::class, 'no_transaksi_sp', 'no_transaksi_sp')
                    ->where('status', 'belum');
    }

}
