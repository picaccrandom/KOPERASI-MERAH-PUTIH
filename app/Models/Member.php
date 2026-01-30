<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;
    // Ini agar Laravel mengizinkan kolom-kolom ini diisi secara massal
    protected $fillable = ['nik', 'nama_lengkap', 'nomor_hp', 'alamat'];

    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function simpanans()
    {
        return $this->hasMany(Simpanan::class);
    }


    public function transaksiObats()
    {
        return $this->hasMany(TransaksiObat::class);
    }

    public function kreditAnggota()
    {
        return $this->hasOne(KreditAnggota::class);
    }
};
