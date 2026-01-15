<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    // Ini agar Laravel mengizinkan kolom-kolom ini diisi secara massal
    protected $fillable = ['nik', 'nama_lengkap', 'nomor_hp', 'alamat'];
}