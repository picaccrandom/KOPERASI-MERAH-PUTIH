<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $fillable = ['penjualan_id', 'barang_id', 'qty', 'harga_satuan', 'subtotal'];
}
