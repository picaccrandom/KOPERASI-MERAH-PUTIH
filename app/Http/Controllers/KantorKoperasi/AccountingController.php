<?php

namespace App\Http\Controllers\KantorKoperasi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Jurnal;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index()
    {
        $accounts = Account::with('jurnals')->orderBy('kode_akun', 'asc')->get();
        return view('kantor.akuntansi.index', compact('accounts'));
    }

    public function showLedger($id)
    {
        $account = Account::with(['jurnals' => function($q) {
            $q->orderBy('tgl_transaksi', 'asc');
        }])->findOrFail($id);

        return view('kantor.akuntansi.ledger', compact('account'));
    }

    public function showLabaRugi()
    {
        $pendapatans = Account::where('kategori', 'Pendapatan')->with('jurnals')->get();
        $bebans = Account::where('kategori', 'Beban')->with('jurnals')->get();

        $totalPendapatan = $pendapatans->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
        });

        $totalBeban = $bebans->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
        });

        $shu = $totalPendapatan - $totalBeban;

        return view('kantor.akuntansi.labarugi', compact('pendapatans', 'bebans', 'totalPendapatan', 'totalBeban', 'shu'));
    }

    public function showNeraca()
    {
        // 1. Ambil Data Akun
        $asets = Account::where('kategori', 'Aset')->with('jurnals')->get();
        $liabilitas = Account::where('kategori', 'Liabilitas')->with('jurnals')->get();
        $ekuitas = Account::where('kategori', 'Ekuitas')->with('jurnals')->get();

        // 2. Hitung SHU berjalan agar sinkron dengan Laba Rugi
        $totalP = Account::where('kategori', 'Pendapatan')->get()->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
        });
        $totalB = Account::where('kategori', 'Beban')->get()->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
        });
        $shuTahunBerjalan = $totalP - $totalB;

        // 3. Hitung Total Aktiva (Aset)
        $totalAktiva = $asets->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
        });

        // 4. Hitung Total Pasiva (Liabilitas + Ekuitas + SHU)
        $totalLiabilitas = $liabilitas->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
        });
        $totalEkuitas = $ekuitas->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
        });

        $totalPasiva = $totalLiabilitas + $totalEkuitas + $shuTahunBerjalan;

        return view('kantor.akuntansi.neraca', compact(
            'asets', 'liabilitas', 'ekuitas', 
            'shuTahunBerjalan', 'totalAktiva', 'totalPasiva'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required|unique:accounts,kode_akun',
            'nama_akun' => 'required|string|max:100',
            'kategori'  => 'required|in:Aset,Liabilitas,Ekuitas,Pendapatan,Beban',
            'saldo_awal' => 'nullable|numeric'
        ]);

        Account::create([
            'kode_akun' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'kategori'  => $request->kategori,
            'saldo_awal' => $request->saldo_awal ?? 0,
        ]);

        return redirect()->route('akuntansi.index')->with('success', 'Akun Co-A Baru Berhasil Ditambahkan!');
    }
}