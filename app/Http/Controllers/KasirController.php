<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\Transaksi;
use App\Models\Transaksi_SP;
use Illuminate\Http\Request;
use App\Models\KreditAnggota;
use Illuminate\Support\Carbon;
use App\Models\PenjualanDetail;
use App\Models\TransaksiDetail;
use App\Models\BonDetail;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

class KasirController extends Controller {
    public function index() {
        $barangs = Barang::where('stok', '>', 0)->get(); // Hanya ambil barang yang ada stoknya
        $members = Member::all(); // Untuk fitur pilih anggota
        $limitBon = KreditAnggota::all(); // dummy limit harga barang yang bisa dibon
        return view('kasir.index', compact('barangs', 'members', 'limitBon'));
    }

    public function store(Request $request) {

            $transaksi = null;

            DB::transaction(function () use ($request, &$transaksi) {
                $namaMember = Member::where('id', $request->member_id)->first();
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
                    'total_tunai' => $request->total_tunai ?? 0,
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

                    $barang = Barang::find($item['id']);
                    $limitBon = KreditAnggota::find($request->member_id);
                    

                    if ($request->kategori == 'member' && $request->metode_bayar == 'bon') {

                        $Transaksi_SP = Transaksi_SP::create([
                            'no_transaksi_sp' => 'SP-B-'. date('YmdHis'),
                            'tanggal' => $transaksi->tgl_transaksi,
                            'member_id' => $request->member_id,
                            'nama' => $namaMember->nama_lengkap,
                            'COA' => 'Bon',
                            'Debit/Credit' => 'Credit',
                            'Nominal' => $transaksi->total_bon,
                            'Keterangan' => 'Bon Anggota: ' . ($request->catatan ? $request->catatan : $namaMember->nama_lengkap),
                        ]);

                        BonDetail::create([
                            'no_transaksi_sp' => $Transaksi_SP->no_transaksi_sp,
                            'status' => 'belum'
                        ]);
                        // otomatis potong limit
                        $limitBon->decrement('limit', $transaksi->total_bon);
                    }
                    // OTOMATIS POTONG STOK GUDANG 
                    $barang->decrement('stok', $item['qty']);
                }

            });

            // catat log transaksi kasir
            writeLog(
                'Kasir',
                'Create',
                'transaksis',
                $transaksi->id ?? null,
                null,
                json_encode($request->all()),
                'Melakukan transaksi kasir dengan kode transaksi: ' . ($transaksi->kode_transaksi ?? '-'),
                'info',
                'success'
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil!',
                'data' => [
                    'kode_transaksi' => $transaksi->kode_transaksi ?? null
                ]
            ]);
            // return redirect()->route('kasir.struk', ['kode_transaksi' => $transaksi->kode_transaksi]);
        }
        
        public function cetakStruk($kode_transaksi, $kembalian = 0) {
            $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->first();
            $details = TransaksiDetail::where('kode_transaksi', $kode_transaksi)->with('barang')->get();
    
            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }

            return view('kasir.struk', compact('transaksi', 'details', 'kembalian'));
    
        }
    }
       
