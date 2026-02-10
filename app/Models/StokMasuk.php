<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;




class StokMasuk extends Model {
    protected $fillable = ['barang_id', 'jumlah_masuk', 'tanggal_masuk', 'supplier', 'keterangan'];

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}