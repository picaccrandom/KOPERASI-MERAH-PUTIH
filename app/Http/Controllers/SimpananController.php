<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Simpanan;
use Illuminate\Http\Request;
use App\Models\SimpananTransaksi;
use Illuminate\Support\Facades\DB;

class SimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $simpanans = Simpanan::with('member', 'transaksi')->orderBy('created_at', 'desc')->get();
        return view('admin.simpanan', compact('simpanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $SimpanansPokok = SimpananTransaksi::where('jenis', 'pokok')->with('simpanan')->get();
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

            $transaksi = Simpanan::create([
                'kode_simpanan' => 'SPS'. date('YmdHis'),
                'member_id' => $request->member_id,
                'status' => 'aktif',
                ]);

            SimpananTransaksi::create([
                'kode_simpanan' => $transaksi->kode_simpanan,
                'tanggal' => now(),
                'jenis' => $request->jenis,
                'tipe' => 'kredit',
                'nominal' => (float)$request->nominal,
                'catatan' => $request->catatan,
            ]);

            if ($request->jenis == 'Sukarela') {
                $saldoBaru = $transaksi->saldo + (float)$request->nominal;
                $transaksi->increment('saldo', $saldoBaru);
            }
        });

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Simpanan $simpanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Simpanan $simpanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Simpanan $simpanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Simpanan $simpanan)
    {
        //
    }
}
