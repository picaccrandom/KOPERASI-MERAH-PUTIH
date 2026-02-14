<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // <--- 1. TAMBAHKAN INI
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
    use HasApiTokens, HasFactory, Notifiable; // <--- 2. PASANG HasApiTokens DI SINI

    // Tambahkan 'password' agar bisa di-update saat login pertama kali
    protected $fillable = ['nik', 'nama_lengkap', 'nomor_hp', 'alamat', 'email', 'status', 'foto_ktp', 'password'];

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
}