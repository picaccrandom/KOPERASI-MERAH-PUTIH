<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\KreditAnggota;
use App\Models\AngsuranPeminjaman;
use App\Models\Transaksi_SP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\Clock\now;

class PinjamanController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    // public function dashboard()
    // {
    //     return view('dashboard');
    // }
    
    
    public function index()
    {
        $peminjamans = Transaksi_SP::with('member', 'angsuranPeminjamans', 'angsuranBelum')->whereIn('COA', ['Pinjam', 'Bon'])->orderBy('created_at', 'desc')->get();
        return view('Pinjaman', compact('peminjamans'));
    }   

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $members = Member::all();
        $limitAnggotas = KreditAnggota::all();
        return view('Pinjaman-Create', compact('members', 'limitAnggotas'));
    }

    public function detail($no_transaksi_sp)
    {
        // Ambil detail angsuran
        $pinjaman = AngsuranPeminjaman::where('no_transaksi_sp', $no_transaksi_sp)
                    ->orderBy('angsuran_ke', 'asc')
                    ->get();

        // Ambil data induk untuk member_id (karena di tabel detail tidak ada member_id)
        $transaksiInduk = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->first();

        if (!$transaksiInduk) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }

        return view('DetailPinjaman', compact('pinjaman', 'transaksiInduk'));
    }

    public function detailBon($no_transaksi_sp){
        $bon = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->with('member')->first();
        return view('DetailBon', compact('bon'));
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'member_id' => 'required|exists:members,id',
        //     'jenis' => 'required|in:uang,barang',
        //     'total_pinjaman' => 'required|numeric|min:0|max:1000000',
        //     'bunga' => 'nullable|numeric|min:0',
        //     'jatuh_tempo' => 'required|date',
        //     'catatan' => 'nullable|string',
        // ]);
        // dd($request);
        DB::transaction(function () use ($request) {
            
            $totalPinjam = [];
            
            if($request->tenor > 12) {
                $totalPinjam = floatval($request->jumlah_pinjaman * ($request->bunga / 100)/12) + floatval($request->jumlah_pinjaman);
            } else {
                $totalPinjam = floatval($request->jumlah_pinjaman);
            }


            
            // $transaksi = Pinjaman::create([
            //     'kode_pinjaman' => 'SPP'. date('YmdHis'),
            //     'user_id' => $request->user_id,
            //     'member_id' => $request->member_id,
            //     'tanggal_pinjaman' => Carbon::now(),
            //     'tanggal_jatuh_tempo' => Carbon::now()->addMonths(floatval($request->tenor)),
            //     'jenis' => $request->jenis,
            //     'bunga' => $request->bunga,
            //     'jumlah_pinjaman' => (float)$request->jumlah_pinjaman,
            //     'total_pinjaman' => $totalPinjam,
            //     'tenor' => $request->tenor,
            //     'status' => 'aktif',
            //     'catatan' => $request->catatan
            // ]);

            // foreach (range(1, $request->tenor) as $angsuran_ke) {

            //     $tanggal_jatuh_tempo_bayar = Carbon::now()->addMonths($angsuran_ke);
            //     $jumlah_angsuran = $transaksi->total_pinjaman / $request->tenor;

            //     DB::table('angsuran_peminjamen')->insert([
            //         'kode_pinjaman' => $transaksi->kode_pinjaman,
            //         'angsuran_ke' => $angsuran_ke,
            //         'batas_bayar' => $tanggal_jatuh_tempo_bayar,
            //         'jumlah_angsuran' => $jumlah_angsuran,
            //         'created_at' => now(),
            //         'updated_at' => now(),
            //     ]);
            // }
            // $kredit = KreditAnggota::where('member_id', $request->member_id)->first();
            // $kredit->decrement('limit', $transaksi->total_pinjaman);
            // dd($request);
            DB::transaction(function () use ($request, $totalPinjam) {
                $namaMember = Member::where('id', $request->member_id)->first();

                $transaksiSP = Transaksi_SP::create([
                    'no_transaksi_sp' => 'SP-P-'. date('YmdHis'),
                    'tanggal' => Carbon::now(),
                    'member_id' => $request->member_id,
                    'nama' => $namaMember->nama_lengkap,
                    'COA' => 'Pinjam',
                    'Debit/Credit' => 'Credit',
                    'Nominal' => $request->jumlah_pinjaman,
                    'Keterangan' => 'Pinjaman Anggota: ' . $request->catatan ?? '-',
                ]);

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

        });
        
        // dd($request);
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function bayarAngsuran($memberId, $id_angsuran) {
        try {
            DB::beginTransaction();

            $angsuran = AngsuranPeminjaman::findOrFail($id_angsuran);
            $tanggal_bayar = Carbon::now();
            
            // Hitung denda
            $durasiDenda = max(0, Carbon::parse($angsuran->batas_bayar)->diffInDays($tanggal_bayar, false));
            $denda = ($durasiDenda > 0) ? (5000 * $durasiDenda) : 0;
            $totalBayar = $angsuran->jumlah_angsuran + $denda;

            $namaMember = Member::where('id', $memberId)->first();
            
            $angsuran->update([
                'tanggal_bayar' => now(),
                'status' => 'lunas',
                'denda' => $denda
            ]);

            Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-A-'. date('YmdHis'),
                'tanggal' => $tanggal_bayar,
                'member_id' => $memberId,
                'nama' => $namaMember->nama_lengkap ?? 'Anggota',
                'COA' => 'Angsuran',
                'Debit/Credit' => 'Debit',
                'Nominal' => $totalBayar,
                'Keterangan' => 'Pembayaran Angsuran ke-' . $angsuran->angsuran_ke,
            ]);

            $limitKredit = KreditAnggota::where('member_id', $memberId)->first();
            if($limitKredit) {
                $limitKredit->increment('limit', $angsuran->jumlah_angsuran);
            }

            DB::commit();

            // PENTING: Respon JSON untuk AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran Berhasil Dicatat'
                ]);
            }

            return redirect()->back()->with('success', 'Angsuran berhasil dibayar.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function bayarBon($no_transaksi_sp) {
        try {
            DB::beginTransaction();
            $bon = Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->first();
            if (!$bon) {
                throw new \Exception('Data bon tidak ditemukan.');
            }
            // dd($bon);

            Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-LB-'. date('YmdHis'),
                'tanggal' => Carbon::now(),
                'member_id' => $bon->member_id,
                'nama' => $bon->nama ?? 'Anggota',
                'COA' => 'Lunas Bon',
                'Debit/Credit' => 'Debit',
                'Nominal' => $bon->Nominal,
                'Keterangan' => 'Pelunasan Bon: ' . ($bon->Keterangan ?? '-'),
            ]);

            KreditAnggota::where('member_id', $bon->member_id)->increment('limit', $bon->Nominal);
            // Transaksi_SP::where('no_transaksi_sp', $no_transaksi_sp)->delete();

            DB::commit();

            // PENTING: Respon JSON untuk AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran Berhasil Dicatat'
                ]);
            }

            return redirect()->route('pinjaman.index')->with('success', 'Bon berhasil dibayar.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}