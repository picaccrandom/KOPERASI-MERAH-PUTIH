<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonDetail extends Model
{
    protected $table = 'bon_details';

    protected $fillable = [
        'no_transaksi_sp',
        'status',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi_SP::class, 'no_transaksi_SP', 'no_transaksi_SP');
    }
}
