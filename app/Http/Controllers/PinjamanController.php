<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\KreditAnggota;
use App\Models\AngsuranPeminjaman;
use App\Models\Transaksi_SP;
use App\Models\BonDetail;
use App\Models\SimpananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AccountingService; // Import Service Akuntansi
use Symfony\Component\HttpFoundation\Response;

class PinjamanController extends Controller
{
    public function dashboardSP()
    {
        return view('simpanpinjam');
    }
    
    public function index()
    {
        $peminjamans = Transaksi_SP::with('member', 'angsuranPeminjamans', 'angsuranBelum')
            ->where('COA', 'Pinjam')
            ->orderBy('created_at', 'desc')
            ->get();

        // $allPeminjamans = SimpananDetail::select('simpanan_details.*', 'transaksi__s_p_s.COA as coa', 'transaksi__s_p_s.member_id')
        //     ->Join('transaksi__s_p_s', 'simpanan_details.no_transaksi_sp', '=', 'transaksi__s_p_s.no_transaksi_sp')
        //     ->Join('members', 'transaksi__s_p_s.member_id', '=', 'members.id')
        //     ->whereIn('transaksi__s_p_s.COA', ['Pinjam', 'Angsuran'])
        //     ->with('member')
        //     ->orderBy('simpanan_details.created_at', 'desc')
        //     ->get();
        return view('simpanpinjam.Pinjaman', compact('peminjamans'));
    }   

    public function indexBon() 
    {
        $Bons = Transaksi_SP::with('member', 'bonBelum')
            ->where('COA', 'Bon')
            ->whereHas('bonBelum')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('simpanpinjam.Bon', compact('Bons'));
    }

    public function create()
    {
        $members = Member::all();
        $limitAnggotas = KreditAnggota::all();
        return view('simpanpinjam.Pinjaman-Create', compact('members', 'limitAnggotas'));
    }

    public function detail($no_transaksi_sp)
    {
        $pinjaman = AngsuranPeminjaman::where('no_transaksi_sp', $no_transaksi_sp)
                    ->orderBy('angsuran_ke', 'asc')
                    ->get();

        $transaksiInduk = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)
            ->with('member', 'angsuranPeminjamans')
            ->first();

        if (!$transaksiInduk) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        return view('simpanpinjam.DetailPinjaman', compact('pinjaman', 'transaksiInduk'));
    }

    public function detailBon($no_transaksi_sp){
        $bon = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->with('member')->first();
        return view('simpanpinjam.DetailBon', compact('bon'));
    }

    /**
     * Store: Pencairan Pinjaman + Jurnal Otomatis
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            
            $totalPinjam = ($request->tenor > 12) 
                ? floatval($request->jumlah_pinjaman * ($request->bunga / 100)/12) + floatval($request->jumlah_pinjaman)
                : floatval($request->jumlah_pinjaman);

            $namaMember = Member::where('id', $request->member_id)->first();

            // 1. Buat Transaksi Pinjaman (Unit SP)
            $transaksiSP = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-P-'. date('YmdHis'),
                'tanggal' => Carbon::now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Pinjam',
                'Debit/Credit' => 'Credit', // Uang keluar dari kas
                'Nominal' => (float)$request->jumlah_pinjaman,
                'Keterangan' => 'Pinjaman Anggota: ' . ($request->catatan ?? '-'),
            ]);

            /** * 2. INTEGRASI AKUNTANSI: PENCAIRAN
             * Debit: Piutang (1201) | Kredit: Kas (1101)
             */
            // AccountingService::post(now(), "Pencairan Pinjaman: ".$namaMember->nama_lengkap, $transaksiSP->no_transaksi_sp, (float)$request->jumlah_pinjaman, 0, '1201');
            // AccountingService::post(now(), "Pengeluaran Kas Pinjaman (".$transaksiSP->no_transaksi_sp.")", $transaksiSP->no_transaksi_sp, 0, (float)$request->jumlah_pinjaman, '1101');

            $limitAnggotas = KreditAnggota::where('id', $transaksiSP->member_id);
            $limitAnggotas->decrement('limit', $transaksiSP->Nominal);
            
            foreach (range(1, $request->tenor) as $angsuran_ke) {
                $tanggal_jatuh_tempo_bayar = Carbon::now()->addMonths($angsuran_ke);
                $jumlah_angsuran = $totalPinjam / $request->tenor;

                DB::table('angsuran_peminjamen')->insert([
                    'no_transaksi_sp' => $transaksiSP->no_transaksi_sp,
                    'user_id' => $request->user_id,
                    'angsuran_ke' => $angsuran_ke,
                    'batas_bayar' => $tanggal_jatuh_tempo_bayar,
                    'jumlah_angsuran' => $jumlah_angsuran,
                    'total_pinjaman' => $totalPinjam,
                    'denda' => 0,
                    'bunga' => $request->bunga,
                    'tenor' => $request->tenor,
                    'tanggal_pinjaman' => Carbon::now(),
                    'status' => 'belum',
                ]);
            }
        });

        writeLog('Pinjaman', 'Create', 'transaksi__s_p_s', null, null, json_encode($request->all()), 'Pinjaman baru & Jurnal otomatis ID: ' . $request->member_id, 'info', 'success');
        
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil & otomatis terjurnal!');
    }

    /**
     * Bayar Angsuran + Jurnal Otomatis
     */
    public function bayarAngsuran($memberId, $id_angsuran) {
        try {
            DB::beginTransaction();

            $angsuran = AngsuranPeminjaman::findOrFail($id_angsuran);
            $limitPerangsuran = ($angsuran->tenor > 12) 
                ? ($angsuran->total_pinjaman - ($angsuran->jumlah_pinjaman * ($angsuran->bunga / 100)))/12 
                : 0;

            $tanggal_bayar = Carbon::now();
            $durasiDenda = max(0, Carbon::parse($angsuran->batas_bayar)->diffInDays($tanggal_bayar, false));
            $denda = ($durasiDenda > 0) ? (5000 * $durasiDenda) : 0;
            $totalBayar = $angsuran->jumlah_angsuran + $denda;

            $namaMember = Member::where('id', $memberId)->first();
            
            $angsuran->update([
                'tanggal_bayar' => now(),
                'status' => 'lunas',
                'denda' => $denda
            ]);

            $transaksi = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-A-'. date('YmdHis'),
                'tanggal' => $tanggal_bayar,
                'member_id' => $memberId,
                'nama' => $namaMember->nama_lengkap ?? 'Anggota',
                'COA' => 'Angsuran',
                'Debit/Credit' => 'Debit', // Uang masuk
                'Nominal' => $totalBayar,
                'Keterangan' => 'Pembayaran Angsuran ke-' . $angsuran->angsuran_ke,
            ]);

            /** * 3. INTEGRASI AKUNTANSI: ANGSURAN
             * Debit: Kas (1101) | Kredit: Piutang (1201)
             */
            // AccountingService::post(now(), "Terima Angsuran ke-".$angsuran->angsuran_ke." ".$namaMember->nama_lengkap, $transaksi->no_transaksi_sp, $totalBayar, 0, '1101');
            // AccountingService::po    st(now(), "Penurunan Piutang (".$transaksi->no_transaksi_sp.")", $transaksi->no_transaksi_sp, 0, $angsuran->jumlah_angsuran, '1201');

            $limitKredit = KreditAnggota::where('member_id', $memberId)->first();
            if($limitKredit) {
                $limitKredit->increment('limit', $angsuran->jumlah_angsuran - $limitPerangsuran);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Angsuran Berhasil!',
                'data' => [ 'kode_transaksi' => $transaksi->kode_transaksi ]
            ]);
            // return redirect()->back()->with('success', 'Angsuran berhasil & Kas Kantor bertambah!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Bayar Bon + Jurnal Otomatis
     */
    public function bayarBon($no_transaksi_sp) {
        try {
            DB::beginTransaction();
            $bon = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->first();
            $status = BonDetail::where('no_transaksi_sp', $no_transaksi_sp)->first();

            $transaksiSP = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-LB-'. date('YmdHis'),
                'tanggal' => Carbon::now(),
                'member_id' => $bon->member_id,
                'nama' => $bon->nama ?? 'Anggota',
                'COA' => 'Lunas Bon',
                'Debit/Credit' => 'Debit',
                'Nominal' => $bon->Nominal,
                'Keterangan' => 'Pelunasan Bon: ' . ($bon->Keterangan ?? '-'),
            ]);

            /** * 4. INTEGRASI AKUNTANSI: PELUNASAN BON */
            // AccountingService::post(now(), "Pelunasan Bon - ".$bon->nama, $transaksiSP->no_transaksi_sp, $bon->Nominal, 0, '1101');
            // AccountingService::post(now(), "Penutupan Piutang Bon (".$transaksiSP->no_transaksi_sp.")", $transaksiSP->no_transaksi_sp, 0, $bon->Nominal, '1201');

            $status->update(['status' => 'lunas']);
            KreditAnggota::where('member_id', $bon->member_id)->increment('limit', $bon->Nominal);

            DB::commit();
            return redirect()->route('bon.indexBon')->with('success', 'Bon lunas & terjurnal!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($no_transaksi_sp) {
        $transaksi = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->first();
        $angsuran = AngsuranPeminjaman::where('no_transaksi_sp', $no_transaksi_sp)->get();

        DB::transaction(function () use ($transaksi, $angsuran) {
            $limitKredit = KreditAnggota::where('member_id', $transaksi->member_id)->first();
            if($limitKredit) { $limitKredit->increment('limit', $transaksi->Nominal); }
            foreach($angsuran as $angsur) { $angsur->delete(); }
            $transaksi->delete();
        });

        return redirect()->route('pinjaman.index')->with('success', 'Data pinjaman dihapus.');
    }
}