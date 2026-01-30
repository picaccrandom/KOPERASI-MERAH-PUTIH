<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiObatDetail extends Model
{
    protected $table = 'transaksi_obat_details';

    protected $fillable = [
        'transaksi_obat_id',
        'obat_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function transaksiObat()
    {
        return $this->belongsTo(TransaksiFaskes::class, 'kode_transaksi', 'kode_transaksi');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }

}
