<?php

namespace App\Http\Controllers;

use App\Models\DistribusiBarang;
use App\Models\Obat; // Contoh jika mutasi ke stok retail yang sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DistribusiController extends Controller
{
    /**
     * Menampilkan Dashboard Gudang Pusat (Central Hub)
     */
    public function index() {
        $barangs = DistribusiBarang::orderBy('nama_barang', 'asc')->get();
        return view('distribusi.gudang', compact('barangs'));
    }

    /**
     * Skema 1: Pengiriman Luar Desa / Pasar (Faktur)
     */
    public function kirimLuarDesa(Request $request, $id) {
        $request->validate([
            'qty' => 'required|integer|min:1',
            'tujuan' => 'required|string',
        ]);

        $barang = DistribusiBarang::findOrFail($id);

        if ($barang->stok_pusat < $request->qty) {
            return back()->with('error', 'Stok pusat tidak mencukupi untuk pengiriman truk!');
        }

        DB::transaction(function () use ($barang, $request) {
            $barang->decrement('stok_pusat', $request->qty);

            // No Faktur Otomatis: DIST-[TANGGAL]-[RANDOM]
            $noFaktur = 'DIST-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            // Simpan log pengiriman luar desa (opsional jika sudah buat tabel faktur)
        });

        return back()->with('success', "Faktur berhasil dibuat. Barang dalam proses kirim ke {$request->tujuan}.");
    }

    /**
     * Skema 2: Mutasi Internal ke Gerai Koperasi
     */
    public function mutasiKeGerai(Request $request, $id) {
        $request->validate([
            'qty_mutasi' => 'required|integer|min:1'
        ]);

        $barang = DistribusiBarang::findOrFail($id);

        if ($barang->stok_pusat < $request->qty_mutasi) {
            return back()->with('error', 'Gagal mutasi! Stok di Gudang Pusat tidak cukup.');
        }

        DB::transaction(function () use ($barang, $request) {
            // Kurangi stok partai besar di pusat
            $barang->decrement('stok_pusat', $request->qty_mutasi);
            

        });

        return back()->with('success', "Berhasil mutasi {$request->qty_mutasi} {$barang->satuan_besar} ke Gerai Koperasi.");
    }

    /**
     * Simpan Barang Masuk ke Gudang Distribusi
     */
    public function store(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:distribusi_barangs',
            'nama_barang' => 'required',
            'stok_pusat' => 'required|integer',
            'satuan_besar' => 'required', // KARUNG, TON, dsb.
            'harga_per_satuan' => 'required|numeric'
        ]);

        DistribusiBarang::create($request->all());

        return back()->with('success', 'Barang partai besar berhasil masuk gudang pusat.');
    }

    // Fungsi untuk Pengiriman Luar Desa (Faktur)
    public function prosesFaktur(Request $request, $id) {
        $request->validate([
            'qty_faktur' => 'required|integer|min:1',
            'tujuan' => 'required|string',
            'nopol' => 'required|string'
        ]);

        $barang = DistribusiBarang::findOrFail($id);
        if ($barang->stok_pusat < $request->qty_faktur) return back()->with('error', 'Stok tidak cukup!');

        DB::transaction(function () use ($barang, $request) {
            $barang->decrement('stok_pusat', $request->qty_faktur);
        });

        return back()->with('success', 'Faktur berhasil dibuat, barang siap dikirim truk!');
    }

    // Fungsi untuk Mutasi Internal ke Gerai
    public function prosesMutasi(Request $request, $id) {
        $request->validate(['qty_mutasi' => 'required|integer|min:1']);
        
        $barang = DistribusiBarang::findOrFail($id);
        if ($barang->stok_pusat < $request->qty_mutasi) return back()->with('error', 'Stok tidak cukup!');

        DB::transaction(function () use ($barang, $request) {
            $barang->decrement('stok_pusat', $request->qty_mutasi);
        });

        return back()->with('success', 'Berhasil mutasi barang ke Gerai Koperasi!');
    }
}