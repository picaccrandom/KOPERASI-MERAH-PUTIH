<?php

namespace App\Http\Controllers\KantorKoperasi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Jurnal;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /**
     * Menampilkan Dashboard Keuangan & Form Pengeluaran
     *
     */
    public function index()
    {
        // 1. Ambil semua transaksi pengeluaran (hanya kategori Beban)
        $pengeluarans = Jurnal::whereHas('account', function($q){
            $q->where('kategori', 'Beban');
        })->with('account')->orderBy('created_at', 'desc')->get();

        // 2. Ambil daftar akun beban untuk isi dropdown di form
        $accounts = Account::where('kategori', 'Beban')->get();

        // 3. Ambil data Kas Utama (1101) untuk ditampilkan di widget saldo
        $kasUtama = Account::where('kode_akun', '1101')->first();

        return view('kantor.keuangan.index', compact('pengeluarans', 'accounts', 'kasUtama'));
    }

    /**
     * Simpan Pengeluaran Kas & Jurnal Otomatis dengan Proteksi Saldo Minus
     *
     */
    public function store(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'required|string|max:255'
        ]);

        try {
            // Gunakan Transaction agar jika satu gagal, semua batal (Atomic)
            return DB::transaction(function () use ($request) {
                
                $akunBeban = Account::findOrFail($request->account_id);
                $kasAccount = Account::where('kode_akun', '1101')->first();

                // --- VALIDASI SALDO KAS ---
                if (!$kasAccount) {
                    throw new \Exception("Akun Kas Utama (1101) belum dibuat di Co-A!");
                }

                if ($kasAccount->saldo_awal < $request->nominal) {
                    throw new \Exception("Saldo Kas Tidak Cukup! Sisa saldo saat ini: Rp " . number_format($kasAccount->saldo_awal, 0, ',', '.'));
                }
                // --------------------------------------------

                /** * LOGIKA DOUBLE ENTRY JURNAL
                 * 1. Debit Akun Beban (Biaya bertambah)
                 * 2. Kredit Akun Kas (Harta berkurang)
                 */
                
                // Posting ke Akun Beban (Debit)
                AccountingService::catatJurnal(
                    $akunBeban->kode_akun, 
                    $request->nominal, 
                    strtoupper($request->keterangan), 
                    'debit'
                );

                // Posting ke Kas Koperasi (Kredit)
                AccountingService::catatJurnal(
                    $kasAccount->kode_akun, 
                    $request->nominal, 
                    "PENGELUARAN: " . strtoupper($request->keterangan), 
                    'kredit'
                );

                return redirect()->back()->with('success', 'Transaksi Berhasil! Saldo kas telah diperbarui secara otomatis.');
            });

        } catch (\Exception $e) {
            // Jika saldo kurang atau error lainnya, kirim pesan error ke view
            return redirect()->back()->with('error', 'Gagal Mencatat: ' . $e->getMessage());
        }
    }
}