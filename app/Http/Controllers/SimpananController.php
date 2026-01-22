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
        $simpanans = Transaksi_SP::with('member', 'simpananDetails')->where('COA', 'Simpan')->orderBy('created_at', 'desc')->get();
        return view('simpanan', compact('simpanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $SimpanansPokok = SimpananDetail::where('jenis', 'pokok')
            ->whereHas('transaksiSP', function ($q) {
                $q->whereNotNull('member_id');
            })
            ->with('transaksiSP.member')
            ->get();
        return view('simpanan-create', compact('members', 'SimpanansPokok'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'member_id' => 'required|exists:members,id',
        //     'nominal' => 'required|numeric|min:0',
        //     'catatan' => 'nullable|string',
        // ]);

        // dd($request)

        DB::transaction(function () use ($request) {

            // $transaksi = Simpanan::create([
            //     'kode_simpanan' => 'SPS'. date('YmdHis'),
            //     'member_id' => $request->member_id,
            //     'status' => 'aktif',
            //     ]);

            // SimpananTransaksi::create([
            //     'kode_simpanan' => $transaksi->kode_simpanan,
            //     'tanggal' => now(),
            //     'jenis' => $request->jenis,
            //     'tipe' => 'kredit',
            //     'nominal' => (float)$request->nominal,
            //     'catatan' => $request->catatan,
            // ]);

            // if ($request->jenis == 'Sukarela') {
            //     $saldoBaru = $transaksi->saldo + (float)$request->nominal;
            //     $transaksi->increment('saldo', $saldoBaru);
            // }


            $transaksi = Transaksi_SP::create([
                $namaMember = Member::where('id', $request->member_id)->first(),
                'no_transaksi_sp' => 'SP-S-'. date('YmdHis'),
                'tanggal' => now(),
                'member_id' => $request->member_id,
                'nama' => $namaMember->nama_lengkap,
                'COA' => 'Simpan',
                'Debit/Credit' => 'Debit',
                'Nominal' => (float)$request->nominal,
                'Keterangan' => 'Simpanan anggota :'.$request->catatan,
            ]);

            SimpananDetail::create([
                'no_transaksi_sp' => $transaksi->no_transaksi_sp,
                'tanggal' => now(),
                'saldo' => (float)$request->nominal,
                'jenis' => $request->jenis,
                'status' => 'aktif',
            ]);
        });

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaksi = Transaksi_SP::with(['member', 'simpananDetails'])
            ->where('id', $id)
            ->where('COA', 'Simpan')
            ->firstOrFail();
        
        return view('detailsimpanan', compact('transaksi'));
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
}
