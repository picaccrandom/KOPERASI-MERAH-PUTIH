<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $fillable = ['kode_transaksi', 'barang_id', 'qty', 'harga_satuan', 'subtotal'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'kode_transaksi');
    }
       
}
