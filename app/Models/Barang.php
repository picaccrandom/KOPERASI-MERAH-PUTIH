<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori', 'stok', 'satuan', 'harga_beli', 'harga_jual'
    ];
}
