<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Obat;
use App\Models\orderObat;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Models\TransaksiFaskes;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiObatDetail;
use App\Services\AccountingService;
use App\Models\Account;

class ApotekController extends Controller
{
    /**
     * DASHBOARD GUDANG (STOK INDUK)
     */
    public function gudangIndex()
    {
        $obats = Obat::orderBy('nama_obat', 'asc')->get();
        return view('apotek.gudang', compact('obats'));
    }

    /**
     * TAMBAH OBAT BARU
     */
    public function storeObat(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|unique:obats',
            'nama_obat' => 'required',
            'harga_jual' => 'required|numeric',
            'satuan' => 'required',
        ]);

        Obat::create($request->all());
        return back()->with('success', 'Obat baru berhasil ditambahkan!');
    }

    /**
     * KIRIM OBAT KE APOTEK (MUTASI STOK)
     */
    public function kirimKeApotek(Request $request, $id)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);
        $obat = Obat::findOrFail($id);

        if ($obat->stok_gudang < $request->jumlah) {
            return back()->with('error', 'Gagal! Stok di Gudang tidak mencukupi.');
        }

        DB::transaction(function () use ($obat, $request) {
            $obat->decrement('stok_gudang', $request->jumlah);
            $obat->increment('stok_apotek', $request->jumlah);
        });

        return back()->with('success', "Berhasil mengirim {$request->jumlah} {$obat->nama_obat} ke Apotek.");
    }

    /**
     * KASIR RETAIL (STOK UNTUK PASIEN UMUM)
     */
    public function apotekIndex()
    {
        $obats = Obat::where('stok_apotek', '>', 0)->get();
        $resepMasuks = orderObat::with('pendaftaranKlinik.member')
                        ->where('status', 'belum')
                        ->orderBy('tanggal_order', 'desc')
                        ->get();  
        return view('apotek.index', compact('obats', 'resepMasuks'));
    }

    /**
     * ANTRIAN RESEP DARI KLINIK
     */
    public function resepMasukIndex()
    {
        $resepMasuks = orderObat::with(['pendaftaranKlinik.member', 'pendaftaranKlinik.rekamMedis'])
                        ->where('status', 'belum')
                        ->orderBy('tanggal_order', 'desc')
                        ->get();
        $obats = Obat::all();
        $rekamMedis = RekamMedis::with('pendaftaranKlinik.member')->get();
        return view('apotek.resep_masuk', compact('resepMasuks', 'obats', 'rekamMedis'));
    }

    /**
     * PROSES BAYAR RESEP KLINIK (INTEGRASI AKUNTANSI)
     */
    public function bayarOrder(Request $request) 
    {
        $request->validate([
            'resep_obat.*' => 'nullable|exists:obats,kode_obat',
            'qty.*' => 'nullable|integer|min:1',
            'pendaftaran_klinik_id' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $member = Member::find($request->member_id);
                $noInvoice = 'INV-APT-' . date('YmdHis');
                $totalNominal = 0;

                // Create Invoice Header
                $transaksiFaskes = TransaksiFaskes::create([
                    'kode_transaksi' => $noInvoice,
                    'tanggal' => now(),
                    'member_id' => $request->member_id,
                    'user_id' => auth()->id(),
                    'nama' => $member->nama_lengkap ?? 'Pasien Klinik',
                    'COA' => 'Apotek',
                    'status' => 'closed',
                    'Debit/Credit' => 'Debit',
                    'Nominal' => 0,
                ]);

                foreach ($request->resep_obat as $index => $kodeObat) {
                    if ($kodeObat) {
                        $obat = Obat::where('kode_obat', $kodeObat)->first();
                        $qty = (int)$request->qty[$index];

                        if ($obat->stok_apotek < $qty) {
                            throw new \Exception("Stok {$obat->nama_obat} tidak cukup!");
                        }

                        $subtotal = $obat->harga_jual * $qty;
                        $totalNominal += $subtotal;

                        $obat->decrement('stok_apotek', $qty);
                        TransaksiObatDetail::create([
                            'kode_transaksi' => $noInvoice,
                            'obat_id' => $obat->id,
                            'nama_obat' => $obat->nama_obat,
                            'qty' => $qty,
                            'subtotal' => $subtotal,
                        ]);
                    }
                }

                $transaksiFaskes->update(['Nominal' => $totalNominal]);

                // Jurnal Akuntansi (1101 & 4101)
                $akunKas = Account::where('kode_akun', '1101')->first();
                $akunPendapatan = Account::where('kode_akun', '4101')->first();

                if ($akunKas && $akunPendapatan) {
                    AccountingService::catatJurnal($akunKas->id, $totalNominal, "Resep: {$noInvoice}", 'debit');
                    AccountingService::catatJurnal($akunPendapatan->id, $totalNominal, "Pendapatan Apotek", 'kredit');
                }

                orderObat::where('pendaftaran_klinik_id', $request->pendaftaran_klinik_id)
                          ->update(['status' => 'selesai']);
            });

            return redirect()->route('apotek.resep')->with('success', 'Resep berhasil diproses!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * PROSES JUAL RETAIL (OTS / UMUM)
     */
    public function prosesJual(Request $request, $id) 
    {
        $request->validate(['qty' => 'required|integer|min:1']);
        $obat = Obat::findOrFail($id);

        if ($obat->stok_apotek < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        try {
            DB::transaction(function () use ($obat, $request) {
                $totalBayar = $obat->harga_jual * $request->qty;
                $noInvoice = 'INV-APT-' . date('YmdHis');

                TransaksiFaskes::create([
                    'kode_transaksi' => $noInvoice,
                    'tanggal' => now(),
                    'nama' => 'Member OTS',
                    'COA' => 'Apotek',
                    'status' => 'closed',
                    'Debit/Credit' => 'Debit',
                    'Nominal' => $totalBayar,
                    'Keterangan' => 'Jual: ' . $obat->nama_obat,
                ]);

                TransaksiObatDetail::create([
                    'kode_transaksi' => $noInvoice,
                    'obat_id' => $obat->id,
                    'nama_obat' => $obat->nama_obat,
                    'qty' => $request->qty,
                    'subtotal' => $totalBayar,
                ]);

                $obat->decrement('stok_apotek', $request->qty);

                $akunKas = Account::where('kode_akun', '1101')->first();
                $akunPendapatan = Account::where('kode_akun', '4101')->first();

                if ($akunKas && $akunPendapatan) {
                    AccountingService::catatJurnal($akunKas->id, $totalBayar, "Jual Retail: {$obat->nama_obat}", 'debit');
                    AccountingService::catatJurnal($akunPendapatan->id, $totalBayar, "Pendapatan Apotek", 'kredit');
                }
            });

            return redirect()->route('apotek.index')->with('success', 'Obat terjual!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * KERANJANG BELANJA (CART RETAIL)
     */
    public function prosesPembayaranCart(Request $request) {
        $cartData = json_decode($request->cart_data, true);
        if (!$cartData) return back()->with('error', 'Keranjang kosong.');

        try {
            DB::transaction(function () use ($cartData) {
                $kode_transaksi = 'INV-APT-' . date('YmdHis');
                $total = 0;

                foreach ($cartData as $item) {
                    $obat = Obat::findOrFail($item['id']);
                    $qty = (int)$item['qty'];
                    $subtotal = $obat->harga_jual * $qty;
                    $total += $subtotal;

                    $obat->decrement('stok_apotek', $qty);
                    TransaksiObatDetail::create([
                        'kode_transaksi' => $kode_transaksi,
                        'obat_id' => $obat->id,
                        'nama_obat' => $obat->nama_obat,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ]);
                }

                TransaksiFaskes::create([
                    'kode_transaksi' => $kode_transaksi,
                    'tanggal' => now(),
                    'nama' => 'Penjualan Keranjang',
                    'COA' => 'Apotek',
                    'status' => 'closed',
                    'Debit/Credit' => 'Debit',
                    'Nominal' => $total,
                ]);

                $akunKas = Account::where('kode_akun', '1101')->first();
                $akunPendapatan = Account::where('kode_akun', '4101')->first();
                if ($akunKas && $akunPendapatan) {
                    AccountingService::catatJurnal($akunKas->id, $total, "Jual Cart: {$kode_transaksi}", 'debit');
                    AccountingService::catatJurnal($akunPendapatan->id, $total, "Pendapatan Apotek", 'kredit');
                }
            });
            return redirect()->route('apotek.index')->with('success', 'Pembayaran Keranjang Berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function hapusResep($id) 
    {
        orderObat::findOrFail($id)->delete();
        return back()->with('success', 'Resep dihapus.');
    }

    public function historiPenjualan()
    {
        // Mengambil data transaksi khusus Apotek yang sudah lunas/selesai
        $histori = TransaksiFaskes::with(['member'])
                    ->where('COA', 'Apotek')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('apotek.histori', compact('histori'));
    }

    public function detailHistori($kode_transaksi)
    {
        // Ambil data detail obat berdasarkan kode invoice
        $details = TransaksiObatDetail::where('kode_transaksi', $kode_transaksi)->get();
        
        return response()->json($details);
    }
}