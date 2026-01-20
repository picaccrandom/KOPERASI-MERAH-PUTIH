<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pinjaman;
use Illuminate\Http\Request;
use App\Models\KreditAnggota;
use App\Models\AngsuranPeminjaman;

use Illuminate\Support\Facades\DB;
use function Symfony\Component\Clock\now;

class PinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjamans = Pinjaman::with('member')->orderBy('created_at', 'desc')->get();
        return view('admin.pinjaman', compact('peminjamans'));
    }

    public function simpanpinjam()
    {
        return view('admin.simpanpinjam');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $limitAnggotas = KreditAnggota::with('member')->get();
        $members = Member::all();
        return view('admin.pinjaman-create', compact('limitAnggotas', 'members'));
    }

    public function detail($id)
    {
        $pinjaman = Pinjaman::with('member', 'angsuranPeminjamans')->findOrFail($id);
        return view('DetailPinjaman', compact('pinjaman'));
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


            
            $transaksi = Pinjaman::create([
                'kode_pinjaman' => 'SPP'. date('YmdHis'),
                'user_id' => $request->user_id,
                'member_id' => $request->member_id,
                'tanggal_pinjaman' => Carbon::now(),
                'tanggal_jatuh_tempo' => Carbon::now()->addMonths(floatval($request->tenor)),
                'jenis' => $request->jenis,
                'bunga' => $request->bunga,
                'jumlah_pinjaman' => $request->jumlah_pinjaman,
                'total_pinjaman' => $totalPinjam,
                'tenor' => $request->tenor,
                'status' => 'aktif',
                'catatan' => $request->catatan
            ]);

            foreach (range(1, $request->tenor) as $angsuran_ke) {

                $tanggal_jatuh_tempo_bayar = Carbon::now()->addMonths($angsuran_ke);
                $jumlah_angsuran = $transaksi->total_pinjaman / $request->tenor;

                DB::table('angsuran_peminjamen')->insert([
                    'kode_pinjaman' => $transaksi->kode_pinjaman,
                    'angsuran_ke' => $angsuran_ke,
                    'batas_bayar' => $tanggal_jatuh_tempo_bayar,
                    'jumlah_angsuran' => $jumlah_angsuran,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $kredit = KreditAnggota::where('member_id', $request->member_id)->first();
            $kredit->decrement('limit', $transaksi->total_pinjaman);

        });
        
        // dd($request);
        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan.');
        
    }

    public function bayarAngsuran($memberId, $id_angsuran) {
        DB::transaction(function () use ($id_angsuran, $memberId) {
            $angsuran = AngsuranPeminjaman::findOrFail($id_angsuran);
            $angsuran->update([
                'tanggal_bayar' => now(),
                'status' => 'lunas'
            ]);

            // if($angsuran->pinjaman->angsuranPeminjamans()->where('status', 'belum')->count() == 0) {
            //     $angsuran->pinjaman->update([
            //         'status' => 'lunas'
            //     ]);
            // }
            
            $limitKredit = KreditAnggota::where('member_id', $memberId)->first();
            $limitKredit->increment('limit', $angsuran->jumlah_angsuran);
        });
        return redirect()->route('pinjaman.index')->with('success', 'Angsuran berhasil dibayar.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pinjaman $pinjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pinjaman $pinjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pinjaman $pinjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pinjaman $pinjaman)
    {
        //
    }
}
