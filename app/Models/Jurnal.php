<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tgl_transaksi',
        'keterangan',
        'referensi', // Diisi No Invoice/Faktur dari Excel Mas
        'debit',
        'kredit',
        'account_id'
    ];

    /**
     * Relasi ke Account (Inverse).
     * Setiap baris jurnal wajib merujuk ke satu akun Co-A.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}