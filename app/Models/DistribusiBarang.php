<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiBarang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'distribusi_barangs';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok_pusat',
        'satuan_besar', // Skala Karung/Ton
        'harga_per_satuan'
    ];
}