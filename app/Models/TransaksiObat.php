<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiObat extends Model
{
    protected $table = 'transaksi_obats';

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
    ];
}
