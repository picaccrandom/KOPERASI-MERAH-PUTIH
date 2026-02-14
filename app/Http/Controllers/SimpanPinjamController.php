<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi_SP;
use App\Models\AngsuranPeminjaman;
use App\Models\SimpananDetail;
use App\Models\Member;
use App\Models\BonDetail;
use Carbon\Carbon;

class SimpanPinjamController extends Controller
{
    // Kembalikan halaman utama
    public function index()
    {
        // SIMPANAN STATISTICS
        // Total Simpanan dan jumlah anggota dengan simpanan
        $totalSimpananOrig = SimpananDetail::sum('saldo');
        $jumlahAnggotaSimpanan = SimpananDetail::distinct('no_transaksi_sp')
            ->join('transaksi__s_p_s', 'simpanan_details.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // Simpanan Pokok
        $totalSimpananPokok = SimpananDetail::where('jenis', 'pokok')->sum('saldo');
        $jumlahAnggotaPokokPokok = SimpananDetail::where('jenis', 'pokok')
            ->distinct('no_transaksi_sp')
            ->join('transaksi__s_p_s', 'simpanan_details.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // Simpanan Wajib
        $totalSimpananWajib = SimpananDetail::where('jenis', 'wajib')->sum('saldo');
        $jumlahAnggotaWajib = SimpananDetail::where('jenis', 'wajib')
            ->distinct('no_transaksi_sp')
            ->join('transaksi__s_p_s', 'simpanan_details.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // Simpanan Sukarela
        $totalSimpananSukarela = SimpananDetail::where('jenis', 'sukarela')->sum('saldo');
        $jumlahAnggotaSukarela = SimpananDetail::where('jenis', 'sukarela')
            ->distinct('no_transaksi_sp')
            ->join('transaksi__s_p_s', 'simpanan_details.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // PINJAMAN STATISTICS
        // Total Pinjaman (dari kolom Nominal yang COA='Pinjam')
        $totalPinjaman = Transaksi_SP::where('COA', 'Pinjam')
            ->sum('Nominal');
        
        $jumlahAnggotaPinjaman = Transaksi_SP::where('COA', 'Pinjam')
            ->distinct('member_id')
            ->count('member_id');

        // Pinjaman Aktif (dari jumlah_angsuran dengan status='belum')
        $totalPinjamanAktif = AngsuranPeminjaman::where('status', 'belum')
            ->sum('jumlah_angsuran');

        $jumlahAnggotaPinjamanAktif = AngsuranPeminjaman::where('status', 'belum')
            ->distinct('user_id')
            ->join('transaksi__s_p_s', 'angsuran_peminjamen.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // Pinjaman Jatuh Tempo 7 Hari (tanggal_bayar dalam 7 hari ke depan dan status='belum')
        $tujuhHariKedepan = Carbon::now()->addDays(7);
        $totalPinjamanUrgent = AngsuranPeminjaman::where('status', 'belum')
            ->whereBetween('tanggal_bayar', [Carbon::now(), $tujuhHariKedepan])
            ->sum('jumlah_angsuran');

        $jumlahAnggotaPinjamanUrgent = AngsuranPeminjaman::where('status', 'belum')
            ->whereBetween('tanggal_bayar', [Carbon::now(), $tujuhHariKedepan])
            ->distinct('user_id')
            ->join('transaksi__s_p_s', 'angsuran_peminjamen.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
            ->distinct('transaksi__s_p_s.member_id')
            ->count('transaksi__s_p_s.member_id');

        // BON STATISTICS
        // Total Bon Belum Lunas (dari tabel transaksi_s_p_s dengan COA='Bon')
        $totalBonBelumLunas = Transaksi_SP::where('COA', 'Bon')
            ->sum('Nominal');
        
        $jumlahAnggotaBon = Transaksi_SP::where('COA', 'Bon')
            ->distinct('member_id')
            ->count('member_id');

        // Total Anggota
        $totalAnggota = Member::count();

        // Pinjaman Terbaru (5 transaksi terakhir)
        $pinjamanTerbaru = Transaksi_SP::where('COA', 'Pinjam')
            ->with('member', 'angsuranBelum')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($pinjaman) {
                return [
                    'member' => $pinjaman->member,
                    'nominal' => $pinjaman->Nominal,
                    'tanggal' => $pinjaman->tanggal,
                    'status' => $pinjaman->angsuranBelum ? 'AKTIF' : 'LUNAS'
                ];
            });

        // Simpanan Terbaru (5 transaksi terakhir)
        $simpananTerbaru = SimpananDetail::with('transaksiSP.member')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->filter(function ($simpanan) {
                return $simpanan->transaksiSP && $simpanan->transaksiSP->member;
            })
            ->map(function ($simpanan) {
                return [
                    'member' => $simpanan->transaksiSP->member,
                    'nominal' => $simpanan->saldo,
                    'tanggal' => $simpanan->tanggal,
                    'jenis' => $simpanan->jenis
                ];
            });

        return view('simpanpinjam.simpanpinjam', [
            // Simpanan Stats
            'totalSimpananOrig' => $totalSimpananOrig,
            'jumlahAnggotaSimpanan' => $jumlahAnggotaSimpanan,
            'totalSimpananPokok' => $totalSimpananPokok,
            'jumlahAnggotaPokok' => $jumlahAnggotaPokokPokok,
            'totalSimpananWajib' => $totalSimpananWajib,
            'jumlahAnggotaWajib' => $jumlahAnggotaWajib,
            'totalSimpananSukarela' => $totalSimpananSukarela,
            'jumlahAnggotaSukarela' => $jumlahAnggotaSukarela,
            // Pinjaman Stats
            'totalPinjaman' => $totalPinjaman,
            'jumlahAnggotaPinjaman' => $jumlahAnggotaPinjaman,
            'totalPinjamanAktif' => $totalPinjamanAktif,
            'jumlahAnggotaPinjamanAktif' => $jumlahAnggotaPinjamanAktif,
            'totalPinjamanUrgent' => $totalPinjamanUrgent,
            'jumlahAnggotaPinjamanUrgent' => $jumlahAnggotaPinjamanUrgent,
            // Bon Stats
            'totalBonBelumLunas' => $totalBonBelumLunas,
            'jumlahAnggotaBon' => $jumlahAnggotaBon,
            // Member Stats
            'totalAnggota' => $totalAnggota,
            // Recent Data
            'pinjamanTerbaru' => $pinjamanTerbaru,
            'simpananTerbaru' => $simpananTerbaru,
        ]);
    }

}