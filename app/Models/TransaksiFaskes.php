<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiFaskes extends Model
{
    protected $table = 'transaksi_faskes';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'member_id',
        'user_id',
        'nama',
        'COA',
        'status',
        'Debit/Credit',
        'Nominal',
        'Keterangan',
        'kode_pendaftaran'
    ];

    public function details()
    {
        return $this->hasMany(TransaksiObatDetail::class, 'kode_transaksi', 'kode_transaksi');
    }

    public function pendaftaranKlinik()
    {
        return $this->belongsTo(PendaftaranKlinik::class, 'kode_pendaftaran', 'no_registrasi');
    }

    public function transaksiObatDetails()
    {
        return $this->hasMany(TransaksiObatDetail::class, 'kode_transaksi', 'kode_transaksi');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
