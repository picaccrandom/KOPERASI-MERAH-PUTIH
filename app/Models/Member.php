<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;
    // Ini agar Laravel mengizinkan kolom-kolom ini diisi secara massal
    protected $fillable = ['nik', 'nama_lengkap', 'nomor_hp', 'alamat'];
}