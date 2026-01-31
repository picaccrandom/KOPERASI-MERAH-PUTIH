<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Member;

class Transaksi extends Model
{

    use SoftDeletes; 

    protected $fillable = [
        'kode_transaksi', 'kategori', 'member_id', 'user_id', 'tgl_transaksi','grand_total', 'tipe_pembayaran', 'status', 'total_tunai', 'total_bon'
    ];

    protected $table = 'transaksis';

    public function barang()
    {
        return $this->hasMany(TransaksiDetail::class, 'kode_transaksi');
    }

    public function kasir() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

}
