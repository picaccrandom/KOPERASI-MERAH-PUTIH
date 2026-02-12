<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Member;
use App\Models\Transaksi;
use App\Models\Transaksi_SP;
use Illuminate\Http\Request;
use App\Models\KreditAnggota;
use App\Models\TransaksiDetail;
use App\Models\BonDetail;
use App\Models\TransaksiFaskes; // Tambahan untuk Laporan
use App\Models\Obat;            // Tambahan untuk Laporan
use App\Models\PendaftaranKlinik; // Tambahan untuk Laporan
use App\Models\SimpananDetail;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{

    public function index()
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        $members = Member::all();
        $limitBon = KreditAnggota::all();
        $SimpananMember = SimpananDetail::where('jenis', 'sukarela')->with('transaksi')->get();
        return view('kasir.index', compact('barangs', 'members', 'limitBon', 'SimpananMember'));
    }

    /**
     * PUSAT LAPORAN TERPADU (5 LAPORAN JADI SATU)
     * Menangani laporan dari Gerai, Apotek, dan Klinik
     */
    public function pusatLaporan(Request $request)
    {
        $type = $request->get('type', 'omzet');
        $tgl_mulai = $request->get('tgl_mulai', date('Y-m-01'));
        $tgl_selesai = $request->get('tgl_selesai', date('Y-m-d'));

        $data = collect();

        switch ($type) {
            case 'omzet':
                $gerai = Transaksi::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])->get();
                $faskes = TransaksiFaskes::whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])->get();
                $data = $gerai->concat($faskes)->sortByDesc('created_at');
                break;

            case 'stok':
                $data = Obat::where('stok_apotek', '<', 15)->orderBy('stok_apotek', 'asc')->get();
                break;

            case 'piutang':
                // 1. Ambil Piutang dari Gerai (Menggunakan kolom total_bon)
                $geraiBon = Transaksi::where('total_bon', '>', 0)
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])->get();

                // 2. Ambil Piutang dari Apotek/Klinik 
                // PERBAIKAN: Gunakan kolom 'Nominal' dan filter 'Debit/Credit' nya adalah 'Credit'
                $faskesBon = TransaksiFaskes::where('Debit/Credit', 'Credit')
                    ->where('Nominal', '>', 0)
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])->get();

                $data = $geraiBon->concat($faskesBon);
                break;

            case 'klinik':
                $data = PendaftaranKlinik::with(['member', 'rekamMedis'])
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])
                    ->get();
                break;

            case 'labarugi':
                $data = TransaksiFaskes::where('status', 'closed')
                    ->whereBetween('created_at', [$tgl_mulai . ' 00:00:00', $tgl_selesai . ' 23:59:59'])->get();
                break;
        }

        return view('kantor.akuntansi.pusat_laporan', compact('data', 'type', 'tgl_mulai', 'tgl_selesai'));
    }

    public function store(Request $request)
    {
        $transaksi = null;

        try {
            DB::transaction(function () use ($request, &$transaksi) {
                $namaMember = Member::find($request->member_id);

                // 1. Simpan Header Penjualan
                $transaksi = Transaksi::create([
                    'kode_transaksi' => 'INV-' . date('YmdHis'),
                    'tgl_transaksi' => now(),
                    'kategori' => $request->kategori,
                    'member_id' => $request->member_id,
                    'user_id' => $request->user_id,
                    'grand_total' => $request->total_harga,
                    'tipe_pembayaran' => $request->metode_bayar,
                    'status' => 'closed',
                    'total_tunai' => $request->total_tunai ?? 0,
                    'total_bon' => $request->total_bon ?? 0,
                    'COA' => 'Gerai'
                ]);

                // 2. Loop barang yang dibeli
                foreach ($request->cart as $item) {
                    TransaksiDetail::create([
                        'kode_transaksi' => $transaksi->kode_transaksi,
                        'barang_id' => $item['id'],
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $item['qty'] * $item['harga'],
                    ]);

                    // Potong Stok
                    $barang = Barang::find($item['id']);
                    if ($barang) {
                        $barang->decrement('stok', $item['qty']);
                    }
                }

                // 3. Logika BON / Piutang
                if ($request->kategori == 'member' && $request->metode_bayar == 'bon') {
                    $Transaksi_SP = Transaksi_SP::create([
                        'no_transaksi_sp' => 'SP-B-' . date('YmdHis'),
                        'tanggal' => $transaksi->tgl_transaksi,
                        'member_id' => $request->member_id,
                        'nama' => $namaMember->nama_lengkap ?? 'Member',
                        'COA' => 'Bon',
                        'Debit/Credit' => 'Debit',
                        'Nominal' => $transaksi->total_bon,
                        'Keterangan' => 'Bon Gerai: ' . ($transaksi->kode_transaksi),
                    ]);

                    BonDetail::create([
                        'no_transaksi_sp' => $Transaksi_SP->no_transaksi_sp,
                        'status' => 'belum'
                    ]);

                    $limitBon = KreditAnggota::where('member_id', $request->member_id)->first();
                    if ($limitBon) {
                        $limitBon->decrement('limit', $transaksi->total_bon);
                    }
                }

                // 4. Logika Simpanan
                if ($request->metode_bayar == 'simpanan') {
                    // buat transaksi simpanan
                    $transaksi_SP = Transaksi_SP::create([
                        'no_transaksi_sp' => 'SP-S-' . date('YmdHis').rand(1000,9999),
                        'tanggal' => $transaksi->tgl_transaksi,
                        'member_id' => $request->member_id,
                        'nama' => $namaMember->nama_lengkap ?? 'Member',
                        'COA' => 'Simpanan',
                        'Debit/Credit' => 'Debit',
                        'Nominal' => $transaksi->grand_total,
                        'Keterangan' => 'Pembayaran Gerai via Simpanan: ' . ($transaksi->kode_transaksi),
                    ]);
                    
                    // buat detail simpanan
                    $simpanan = SimpananDetail::create([
                        'no_transaksi_sp' => $transaksi_SP->no_transaksi_sp,
                        'tanggal' => $transaksi->tgl_transaksi,
                        'saldo' => -$transaksi->grand_total,
                        'biaya_admin' => 0,
                        'jenis' => 'sukarela',
                        'status' => 'aktif',
                    ]);
                }
            });

            if (function_exists('writeLog')) {
                writeLog('Kasir', 'Create', 'transaksis', $transaksi->id, null, json_encode($request->all()), 'Transaksi Gerai: ' . $transaksi->kode_transaksi, 'info', 'success');
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil!',
                'data' => ['kode_transaksi' => $transaksi->kode_transaksi]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cetakStruk($kode_transaksi, $kembalian = 0)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->first();
        $details = TransaksiDetail::where('kode_transaksi', $kode_transaksi)->with('barang')->get();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('kasir.struk', compact('transaksi', 'details', 'kembalian'));
    }
}
