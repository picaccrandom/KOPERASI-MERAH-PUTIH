<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\TransaksiFaskes;
use App\Models\TransaksiObat;
use App\Models\TransaksiObatDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AccountingService;

class ApotekController extends Controller
{
    /**
     * Menampilkan Dashboard Gudang Apotek (Stok Induk)
     */
    public function gudangIndex()
    {
        // Mengambil semua data obat diurutkan berdasarkan nama
        $obats = Obat::orderBy('nama_obat', 'asc')->get();
        return view('apotek.gudang', compact('obats'));
    }

    /**
     * Menyimpan data obat baru ke database
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

        return back()->with('success', 'Obat baru berhasil ditambahkan ke sistem!');
    }

    /**
     * Proses Mutasi Stok: Kirim Obat dari Gudang ke Apotek
     */
    public function kirimKeApotek(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $obat = Obat::findOrFail($id);

        // Validasi kecukupan stok di gudang
        if ($obat->stok_gudang < $request->jumlah) {
            return back()->with('error', 'Gagal! Stok di Gudang tidak mencukupi untuk dikirim.');
        }

        // Database Transaction untuk menjaga integritas data
        DB::transaction(function () use ($obat, $request) {
            // Kurangi stok gudang, tambah stok apotek
            $obat->decrement('stok_gudang', $request->jumlah);
            $obat->increment('stok_apotek', $request->jumlah);

            // Logika ini memastikan total stok tetap sama, hanya berpindah lokasi
        });

        return back()->with('success', "Berhasil mengirim {$request->jumlah} {$obat->satuan} {$obat->nama_obat} ke Apotek.");
    }

    /**
     * Menampilkan Dashboard Apotek Retail (Stok untuk Pasien)
     */
    public function apotekIndex()
    {
        // Ambil obat yang stok apoteknya > 0 untuk dijual
        $obats = Obat::where('stok_apotek', '>', 0)->get();
        return view('apotek.index', compact('obats')); // Pastikan memanggil view 'index'
    }

    public function resepMasukIndex()
    {
        // Ambil data resep masuk dari klinik (dummy data untuk contoh)
        $resepMasuks = TransaksiFaskes::with('member', 'pendaftaranKlinik.rekamMedis', 'transaksiObatDetails.obat')
            ->where('COA', 'Apotek')
            ->where('status', 'open')
            ->get();
        $obats = Obat::all();
        $rekamMedis = RekamMedis::with('pendaftaranKlinik.member', 'pendaftaranKlinik.transaksiFaskesApotek.transaksiObatDetails.obat')->get();
        return view('apotek.resep_masuk', compact('resepMasuks', 'obats', 'rekamMedis'));
    }

    // Menampilkan form penjualan obat
    public function jualObat($id) {
        $obat = Obat::findOrFail($id);
        return view('apotek.jual', compact('obat'));
    }

    public function bayarOrder(Request $request) {
        $request->validate([
            'kode_transaksi' => 'required|exists:transaksi_faskes,kode_transaksi',
            'resep_obat.*' => 'nullable|exists:obats,kode_obat',
            'resep_obat_new.*' => 'nullable|exists:obats,kode_obat',
            'qty.*' => 'nullable|integer|min:1',
            'qty_new.*' => 'nullable|integer|min:1'
        ]);

        
        $kode_transaksi = $request->kode_transaksi;

        $dataObat = TransaksiObatDetail::where('kode_transaksi', $kode_transaksi)->get();
        foreach ($dataObat as $item) {
            $obat = Obat::where('id', $item->obat_id)->first();
            if ($obat && $obat->stok_apotek >= $item->qty) {
                // Kurangi stok apotek
                $obat->decrement('stok_apotek', $item->qty);
            } else {
                return back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi.");
            }
        }
        
        
        if($request->has('resep_obat_new')) {
            foreach($request->resep_obat_new as $index => $kode_obat) {
                $qty = $request->qty_new[$index];

                // Kurangi stok apotek
                $obat = Obat::where('kode_obat', $kode_obat)->first();
                if ($obat && $obat->stok_apotek >= $qty) {
                    $obat->decrement('stok_apotek', $qty);

                    // Simpan detail transaksi obat
                    TransaksiObatDetail::create([
                        'kode_transaksi' => $kode_transaksi,
                        'obat_id' => $obat->id,
                        'nama_obat' => $obat->nama_obat,
                        'qty' => $qty,
                        'subtotal' => $obat->harga_jual * $qty,
                    ]);
                    
                    $totalHargaTambahan = $obat->harga_jual * $qty;
                    
                    // Update total nominal di transaksi faskes
                    $transaksiFaskes = TransaksiFaskes::where('kode_transaksi', $kode_transaksi)->first();
                    if ($transaksiFaskes) {
                        $transaksiFaskes->increment('Nominal', $totalHargaTambahan);
                    }
                } else {
                    return back()->with('error', "Stok obat {$obat->nama_obat} tidak mencukupi.");
                }
            }
            
        } 


        // Cari transaksi faskes untuk member dengan COA 'Apotek' dan status 'open'
        $transaksiFaskes = TransaksiFaskes::where('kode_transaksi', $kode_transaksi)
            ->where('COA', 'Apotek')
            ->where('status', 'open')
            ->first();

        if (!$transaksiFaskes) {
            return back()->with('error', 'Transaksi tidak ditemukan atau sudah ditutup.');
        }

        // Update status transaksi menjadi 'closed'
        $transaksiFaskes->update([
            'status' => 'closed',
            'updated_at' => now(),
            'Debit/Credit' => 'Debit'
        ]);

        return redirect()->route('apotek.index')->with('success', 'Pembayaran resep berhasil dilakukan!');
    }

    // Proses transaksi penjualan
    public function prosesJual(Request $request, $id) {
        $request->validate(['qty' => 'required|integer|min:1']);
        $obat = Obat::findOrFail($id);

        if ($obat->stok_apotek < $request->qty) {
            return back()->with('error', 'Stok apotek tidak mencukupi!');
        }

        // Hitung total harga transaksi
        $totalBayar = $obat->harga_jual * $request->qty;
        $noInvoice = 'INV-APT-' . date('Ymd') . '-' . rand(100, 999); // Referensi unik

        DB::transaction(function () use ($obat, $request, $totalBayar, $noInvoice) {
            // 1. Kurangi stok retail di apotek
            $obat->decrement('stok_apotek', $request->qty);
            
            // 2. Update status resep jika ada
            RekamMedis::where('resep_obat', $obat->kode_obat)->update(['status_resep' => 'selesai']);

            /**
             * 3. OTOMATIS POSTING KE AKUNTANSI (KANTOR KOPERASI)
             * Skema: Debit Kas, Kredit Pendapatan Apotek
             */
            
            // Posting DEBIT ke akun KAS (Gunakan kode akun 1101 sesuai standar)
            AccountingService::post(
                now(), 
                "Penjualan Obat: {$obat->nama_obat} ({$request->qty} {$obat->satuan})", 
                $noInvoice, 
                $totalBayar, 0, // Debit
                '1101' // Kode Akun Kas
            );

            // Posting KREDIT ke akun PENDAPATAN APOTEK (Gunakan kode akun 4101)
            AccountingService::post(
                now(), 
                "Pendapatan Penjualan {$noInvoice}", 
                $noInvoice, 
                0, $totalBayar, // Kredit
                '4101' // Kode Akun Pendapatan Apotek
            );
        });

        return redirect()->route('apotek.index')->with('success', 'Obat terjual & otomatis terjurnal di Kantor Koperasi!');
    }
}
