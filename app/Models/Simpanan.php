<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simpanan extends Model
{

    use softDeletes;
    
    protected $table = 'simpanans';

    protected $fillable = [
        'kode_simpanan',
        'member_id',
        'saldo',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class , 'member_id');
    }

    public function transaksi()
    {
        return $this->hasMany(SimpananTransaksi::class, 'kode_simpanan', 'kode_simpanan');
    }
}
