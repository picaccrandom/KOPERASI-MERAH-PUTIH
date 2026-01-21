<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{

    use SoftDeletes;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'kode_pinjaman',
        'member_id',
        'jenis',
        'total_pinjaman',
        'tanggal_pinjaman',
        'tanggal_jatuh_tempo',
        'bunga',
        'tenor',
        'jumlah_pinjaman',
        'catatan',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class , 'member_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function angsuranPeminjamans()
    {
        return $this->hasMany(AngsuranPeminjaman::class, 'kode_pinjaman' , 'kode_pinjaman');
    }
}
