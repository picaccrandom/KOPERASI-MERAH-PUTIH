<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiObatDetail extends Model
{
    protected $table = 'transaksi_obat_details';

    protected $fillable = [
        'kode_transaksi',
        'obat_id',
        'nama_obat',
        'qty',
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
