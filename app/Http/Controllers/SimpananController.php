<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Simpanan;
use App\Models\SimpananDetail;
use Illuminate\Http\Request;
use App\Models\SimpananTransaksi;
use App\Models\Transaksi_SP;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();
        $simpanans = Transaksi_SP::with('member', 'simpananDetails')->whereIn('COA', ['Simpan', 'Tarik'])->orderBy('created_at', 'desc')->get();
        return view('simpanan', compact('simpanans', 'members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $SimpanansPokok = SimpananDetail::where('jenis', 'POKOK') 
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->with('transaksiSP')
            ->get()
            ->map(function($item) {
                return [
                    'member_id' => $item->transaksiSP->member_id
                ];
            });
            
        return view('simpanan-create', compact('members', 'SimpanansPokok'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $namaMember = Member::where('id', $request->member_id)->first();

        if (!$namaMember) {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }
        // $request->validate([
        //     'member_id' => 'required|exists:members,id',
        //     'nominal' => 'required|numeric|min:0',
        //     'catatan' => 'nullable|string',
        // ]);

        // dd($request)

        DB::transaction(function () use ($request, $namaMember) {

            // 1. Buat Transaksi Simpanan
            $transaksi = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-S-' . date('YmdHis'),
                'tanggal' => now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Simpan',
                'Debit/Credit' => 'Debit', // Uang masuk
                'Nominal' => (float)$request->nominal,
                'Keterangan' => 'Simpanan ' . $request->jenis . ': ' . ($request->catatan ?? '-'),
            ]);

            // 2. Buat Detail Simpanan
            SimpananDetail::create([
                'no_transaksi_sp' => $transaksi->no_transaksi_sp,
                'tanggal' => now(),
                'saldo' => (float)$request->nominal,
                'jenis' => strtoupper($request->jenis), // Simpan sebagai POKOK/WAJIB/SUKARELA
                'status' => 'aktif',
            ]);

            // 3. INTEGRASI: Update Buku Kas Umum
            //$saldoTerakhir = DB::table('kas_koperasis')->orderBy('id', 'desc')->value('saldo_akhir') ?? 0;
            //DB::table('kas_koperasis')->insert([
                //'tgl_catat' => now(),
                //'keterangan' => 'Simpanan ' . $request->jenis . ' - ' . $namaMember->nama_lengkap,
                //'masuk' => (float)$request->nominal,
                //'keluar' => 0,
                //'saldo_akhir' => $saldoTerakhir + (float)$request->nominal,
                //'kategori' => 'Simpanan',
                //'created_at' => now(),
                //'updated_at' => now(),
            //]);
        });

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan dan saldo kas diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Jika request AJAX / ingin JSON, kembalikan data member + total simpanan
        if (request()->wantsJson() || request()->expectsJson()) {
            $member = Member::findOrFail($id);

            $total_simpanan = SimpananDetail::whereHas('transaksiSP', function($q) use ($id) {
                $q->where('member_id', $id);
            })->sum('saldo');
            $status = SimpananDetail::whereHas('transaksiSP', function($q) use ($id) {
                $q->where('member_id', $id);
            })->exists() ? 'aktif' : 'nonaktif';

            return response()->json([
                'member' => $member,
                'status' => $status,
                'total_simpanan' => $total_simpanan,
            ]);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaksi = Transaksi_SP::with(['member', 'simpananDetails'])
            ->where('id', $id)
            ->where('COA', 'Simpan')
            ->firstOrFail();
        
        $members = Member::all(); // Untuk dropdown jika perlu
        return view('simpanan-edit', compact('transaksi', 'members'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nominal' => 'required|numeric|min:1000',
            'jenis' => 'required|in:wajib,pokok,sukarela',
            'keterangan' => 'nullable|string|max:255',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        DB::transaction(function () use ($request, $id) {
            // Update transaksi utama
            $transaksi = Transaksi_SP::where('id', $id)
                ->where('COA', 'Simpan')
                ->firstOrFail();

            $transaksi->update([
                'tanggal' => $request->tanggal,
                'Nominal' => (float)$request->nominal,
                'Keterangan' => $request->keterangan,
            ]);

            // Update detail simpanan
            $detail = SimpananDetail::where('no_transaksi_sp', $transaksi->no_transaksi_sp)
                ->firstOrFail();

            $detail->update([
                'tanggal' => $request->tanggal,
                'saldo' => (float)$request->nominal,
                'jenis' => $request->jenis,
                'status' => $request->status ?? $detail->status,
            ]);
        });

        return redirect()->route('simpanan.show', $id)
            ->with('success', 'Data simpanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaksi = Transaksi_SP::where('id', $id)
            ->where('COA', 'Simpan')
            ->firstOrFail();

        DB::transaction(function () use ($transaksi) {
            // Hapus detail simpanan terlebih dahulu
            SimpananDetail::where('no_transaksi_sp', $transaksi->no_transaksi_sp)->delete();
            
            // Hapus transaksi utama
            $transaksi->delete();
        });

        return redirect()->route('simpanan.index')
            ->with('success', 'Data simpanan berhasil dihapus.');
    }

    public function createTarik()
    {
        $members = Member::all();
        
        $saldoSimpanan = SimpananDetail::whereIn('jenis', ['sukarela'])
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->with('transaksiSP')
            ->get()
            ->map(function($item) {
                return [
                    'member_id' => $item->transaksiSP->member_id,
                    'jenis_simpanan' => $item->jenis,
                    'saldo' => $item->saldo,
                ];
            });
        return view('TarikSimpanan', compact('members', 'saldoSimpanan'));
    }

    public function reduce(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'nominal' => 'required|numeric|min:1000',
            'catatan' => 'nullable|string|max:255',
        ]);

        $namaMember = Member::where('id', $request->member_id)->first();

        if (!$namaMember) {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $namaMember) {
            // 1. Buat Transaksi Simpanan
            $transaksi = Transaksi_SP::create([
                'no_transaksi_sp' => 'SP-ST-' . date('YmdHis'),
                'tanggal' => now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Tarik',
                'Debit/Credit' => 'Credit', // Uang keluar
                'Nominal' => (float)$request->nominal,
                'Keterangan' => 'Penarikan Simpanan Sukarela: ' . ($request->catatan ?? '-'),
            ]);

            // 2. Buat Detail Simpanan
            SimpananDetail::create([
                'no_transaksi_sp' => $transaksi->no_transaksi_sp,
                'tanggal' => now(),
                'saldo' => -(float)$request->nominal, // Saldo negatif untuk penarikan
                'jenis' => 'sukarela',
                'status' => 'aktif',
            ]);
        }); 
        return redirect()->route('simpanan.index')->with('success', 'Penarikan simpanan berhasil diproses.');
    }

}
