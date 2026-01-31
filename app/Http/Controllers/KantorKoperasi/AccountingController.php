<?php

namespace App\Http\Controllers\KantorKoperasi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Jurnal;
use App\Models\Member; // Pastikan Model Member sudah ada
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        $accounts = Account::with('jurnals')->orderBy('kode_akun', 'asc')->get();
        return view('kantor.akuntansi.index', compact('accounts'));
    }

    /**
     * Dashboard Statistik: Menampilkan Grafik Pendapatan vs Beban
     */
    public function dashboardStatistik()
    {
        $days = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->format('d M');

            $income = Jurnal::whereHas('account', function($q) {
                $q->where('kategori', 'Pendapatan');
            })->whereDate('tgl_transaksi', $date)->sum('kredit');

            $expense = Jurnal::whereHas('account', function($q) {
                $q->where('kategori', 'Beban');
            })->whereDate('tgl_transaksi', $date)->sum('debit');

            $incomeData[] = $income;
            $expenseData[] = $expense;
        }

        $totalAset = Account::where('kategori', 'Aset')->get()->sum(function($acc) {
            return $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
        });

        $totalPendapatanBulanIni = Jurnal::whereHas('account', function($q) {
            $q->where('kategori', 'Pendapatan');
        })->whereMonth('tgl_transaksi', date('m'))->sum('kredit');

        return view('kantor.akuntansi.dashboard_statistik', compact(
            'days', 'incomeData', 'expenseData', 'totalAset', 'totalPendapatanBulanIni'
        ));
    }

    /**
     * Pusat Laporan: Mengelola Laporan dengan Parameter Tanggal
     */
    public function pusatLaporan(Request $request)
    {
        $type = $request->query('type');
        $tgl_mulai = $request->query('tgl_mulai');
        $tgl_selesai = $request->query('tgl_selesai');
        
        $data = collect();
        $title = "Pilih Parameter Laporan";

        if ($type) {
            switch ($type) {
                case 'pendapatan':
                    $title = "Laporan Pendapatan Keseluruhan";
                    if($tgl_mulai && $tgl_selesai) {
                        $data = Jurnal::whereHas('account', function($q) {
                            $q->where('kategori', 'Pendapatan');
                        })
                        ->whereBetween('tgl_transaksi', [$tgl_mulai, $tgl_selesai])
                        ->orderBy('tgl_transaksi', 'asc')
                        ->get();
                    }
                    break;

                case 'penjualan':
                    $title = "Laporan Penjualan Kasir";
                    if($tgl_mulai && $tgl_selesai) {
                        // Mengambil dari tabel transaksis
                        $data = DB::table('transaksis')
                            ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                            ->orderBy('created_at', 'asc')
                            ->get();
                    }
                    break;

                case 'anggota':
                    $title = "Daftar Seluruh Anggota Koperasi";
                    // PERBAIKAN: Menggunakan kolom 'nama_lengkap' sesuai struktur database Mas Yoga
                    $data = Member::orderBy('nama_lengkap', 'asc')->get();
                    break;
            }
        }

        return view('kantor.akuntansi.pusat_laporan', compact('data', 'title', 'type', 'tgl_mulai', 'tgl_selesai'));
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

        $totalPendapatan = $pendapatans->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit')));
        $totalBeban = $bebans->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit')));
        $shu = $totalPendapatan - $totalBeban;

        return view('kantor.akuntansi.labarugi', compact('pendapatans', 'bebans', 'totalPendapatan', 'totalBeban', 'shu'));
    }

    public function showNeraca()
    {
        $asets = Account::where('kategori', 'Aset')->with('jurnals')->get();
        $liabilitas = Account::where('kategori', 'Liabilitas')->with('jurnals')->get();
        $ekuitas = Account::where('kategori', 'Ekuitas')->with('jurnals')->get();

        $totalP = Account::where('kategori', 'Pendapatan')->get()->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit')));
        $totalB = Account::where('kategori', 'Beban')->get()->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit')));
        $shuTahunBerjalan = $totalP - $totalB;

        $totalAktiva = $asets->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit')));
        $totalLiabilitas = $liabilitas->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit')));
        $totalEkuitas = $ekuitas->sum(fn($acc) => $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit')));

        $totalPasiva = $totalLiabilitas + $totalEkuitas + $shuTahunBerjalan;

        return view('kantor.akuntansi.neraca', compact('asets', 'liabilitas', 'ekuitas', 'shuTahunBerjalan', 'totalAktiva', 'totalPasiva'));
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