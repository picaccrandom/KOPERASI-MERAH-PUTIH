<?php

namespace App\Services;

use App\Models\Jurnal;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Catat Jurnal (Debit/Kredit) Tanpa Update Saldo Statis
     * [PENTING] Kita tidak mengupdate kolom saldo_awal di tabel accounts 
     * agar tidak terjadi double counting saat dashboard menghitung real-time.
     */
    public static function catatJurnal($accountId, $nominal, $keterangan, $tipe = 'debit')
    {
        return DB::transaction(function () use ($accountId, $nominal, $keterangan, $tipe) {
            
            // 1. Generate nomor referensi unik
            $ref = ($tipe == 'debit' ? 'DB-' : 'KR-') . date('YmdHis') . rand(10, 99);

            /**
             * 2. Simpan transaksi ke tabel jurnals
             * Kolom 'debit' atau 'kredit' diisi sesuai tipe transaksi, 
             * sedangkan kolom lawannya diisi 0 agar tidak Null.
             */
            return Jurnal::create([
                'account_id'    => $accountId,
                'tgl_transaksi' => now(),
                'referensi'     => $ref,
                'keterangan'    => strtoupper($keterangan),
                'debit'         => ($tipe == 'debit' ? $nominal : 0),
                'kredit'        => ($tipe == 'kredit' ? $nominal : 0),
            ]);

        });
    }
}