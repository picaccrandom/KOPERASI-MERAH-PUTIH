<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller {
    public function index() {
        $barangs = Barang::where('stok', '>', 0)->get(); // Hanya ambil barang yang ada stoknya
        $members = Member::all(); // Untuk fitur pilih anggota
        return view('admin.kasir', compact('barangs', 'members'));
    }

    public function store(Request $request) {
        return DB::transaction(function () use ($request) {
            // 1. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'no_invoice' => 'INV-' . date('YmdHis'),
                'member_id' => $request->member_id,
                'total_harga' => $request->total_harga,
                'metode_bayar' => $request->metode_bayar,
            ]);

            // 2. Loop barang yang dibeli
            foreach ($request->cart as $item) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal' => $item['qty'] * $item['harga'],
                ]);

                // OTOMATIS POTONG STOK GUDANG
                $barang = Barang::find($item['id']);
                $barang->decrement('stok', $item['qty']);
            }

            // 3. INTEGRASI SIMPAN PINJAM (Jika Metode Bon)
            if ($request->metode_bayar == 'Bon' && $request->member_id) {
                // Tambahkan catatan piutang ke tabel pinjaman/piutang anggota
                DB::table('pinjamans')->insert([
                    'member_id' => $request->member_id,
                    'jumlah' => $request->total_harga,
                    'keterangan' => 'Bon Kasir: ' . $penjualan->no_invoice,
                    'status' => 'Belum Lunas',
                    'created_at' => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Transaksi Berhasil!']);
        });
    }
}