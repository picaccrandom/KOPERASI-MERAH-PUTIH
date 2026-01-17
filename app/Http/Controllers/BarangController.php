<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang; // Cukup import satu kali di sini

class BarangController extends Controller
{
    // 1. Menampilkan Halaman Master Barang
    public function index() {
        $barangs = Barang::all();
        return view('admin.gudang', compact('barangs'));
    }

    // 2. Menyimpan Barang Baru
    public function store(Request $request) {
        $request->validate([
            'kode_barang' => 'required|unique:barangs',
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);

        Barang::create($request->all());
        return back()->with('success', 'Barang berhasil didaftarkan ke gudang!');
    }

    // 3. Update Data Barang
    public function update(Request $request, $id) {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,'.$id,
            'nama_barang' => 'required',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return back()->with('success', 'Barang berhasil diupdate!');
    }

    // 4. Hapus Barang
    public function destroy($id) {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus dari sistem!');
    }

    public function storeStokMasuk(Request $request) 
    {
        $request->validate([
            'barang_id' => 'required',
            'jumlah_masuk' => 'required|numeric|min:1',
            'tanggal_masuk' => 'required|date',
        ]);

        // 1. Update stok di tabel barangs
        $barang = \App\Models\Barang::findOrFail($request->barang_id);
        $barang->stok += $request->jumlah_masuk;
        $barang->save();

        return back()->with('success', 'Stok ' . $barang->nama_barang . ' berhasil ditambah!');
    }
}