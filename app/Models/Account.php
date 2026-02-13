<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'kategori',
        'saldo_awal'
    ];

    /**
     * Relasi One-to-Many ke Jurnal.
     * Satu Akun bisa memiliki banyak baris di jurnal.
     */
    public function jurnals()
    {
        return $this->hasMany(Jurnal::class, 'kode_akun', 'kode_akun');
    }

    /**
     * Fungsi helper untuk menghitung Saldo Akhir akun secara real-time.
     * Sangat berguna untuk Dashboard Kantor Koperasi Mas.
     */
    public function getSaldoAkhirAttribute()
    {
        $totalDebit = $this->jurnals()->sum('debit');
        $totalKredit = $this->jurnals()->sum('kredit');

        // Logika Akuntansi: Aset & Beban bertambah di Debit, sisanya di Kredit.
        if (in_array($this->kategori, ['Aset', 'Beban'])) {
            return ($this->saldo_awal + $totalDebit) - $totalKredit;
        }

        return ($this->saldo_awal + $totalKredit) - $totalDebit;
    }
}