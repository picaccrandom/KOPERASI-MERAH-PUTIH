<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KreditAnggota;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller {
    public function index() {
        $barangs = Barang::where('stok', '>', 0)->get(); // Hanya ambil barang yang ada stoknya
        $members = Member::all(); // Untuk fitur pilih anggota
        $limitBon = KreditAnggota::all(); // dummy limit harga barang yang bisa dibon
        return view('admin.kasir', compact('barangs', 'members', 'limitBon'));
    }

    public function store(Request $request) {
        // return DB::transaction(function () use ($request) {
        //     // 1. Simpan Header Penjualan
        //     $penjualan = Penjualan::create([
        //         'no_invoice' => 'INV-' . date('YmdHis'),
        //         'member_id' => $request->member_id,
        //         'total_harga' => $request->total_harga,
        //         'metode_bayar' => $request->metode_bayar,
        //     ]);

        //     // 2. Loop barang yang dibeli
        //     foreach ($request->cart as $item) {
        //         TransaksiDetail::create([
        //             'penjualan_id' => $penjualan->id,
        //             'barang_id' => $item['id'],
        //             'qty' => $item['qty'],
        //             'harga_satuan' => $item['harga'],
        //             'subtotal' => $item['qty'] * $item['harga'],
        //         ]);

        //         // OTOMATIS POTONG STOK GUDANG
        //         $barang = Barang::find($item['id']);
        //         $barang->decrement('stok', $item['qty']);
        //     }

        //     // 3. INTEGRASI SIMPAN PINJAM (Jika Metode Bon)
        //     if ($request->metode_bayar == 'Bon' && $request->member_id) {
        //         // Tambahkan catatan piutang ke tabel pinjaman/piutang anggota
        //         DB::table('pinjamans')->insert([
        //             'member_id' => $request->member_id,
        //             'jumlah' => $request->total_harga,
        //             'keterangan' => 'Bon Kasir: ' . $penjualan->no_invoice,
        //             'status' => 'Belum Lunas',
        //             'created_at' => now(),
        //         ]);
        //     }

        //     return response()->json(['success' => true, 'message' => 'Transaksi Berhasil!']);
        // });

        // dd($request->all());
        // try {
            DB::transaction(function () use ($request) {

                // Simpan Header Penjualan
                $transaksi = Transaksi::create([
                    'kode_transaksi' => 'INV-' . date('YmdHis'),
                    'tgl_transaksi' => now(),
                    'kategori' => $request->kategori,
                    'member_id' => $request->member_id,
                    'user_id' => $request->user_id,
                    'grand_total' => $request->total_harga,
                    'tipe_pembayaran' => $request->metode_bayar,
                    'status' => $request->status,
                    'total_tunai' => $request->total_tunai,
                    'total_bon' => $request->total_bon,
                ]);
    
                // Loop barang yang dibeli
                foreach ($request->cart as $item) {
                    TransaksiDetail::create([
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'barang_id' => $item['id'],
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $item['qty'] * $item['harga'],
                    ]);

                    // OTOMATIS POTONG STOK GUDANG
                    $barang = Barang::find($item['id']);
                    $barang->decrement('stok', $item['qty']);
                }

            });

            return response()->json(['success' => true, 'message' => 'Transaksi Berhasil!']);
    
        // } catch (\Exception $e) {
        //     \Log::error($e);
        //     return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        // }
        // };
    }   
}