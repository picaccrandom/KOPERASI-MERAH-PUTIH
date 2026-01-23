<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $fillable = [
        'kode_obat', 'nama_obat', 'kategori', 'stok_gudang', 
        'stok_apotek', 'harga_beli', 'harga_jual', 'satuan', 'tgl_kadaluwarsa'
    ];
}
