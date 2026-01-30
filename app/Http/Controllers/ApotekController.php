<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\RekamMedis;
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
        $resepMasuks = RekamMedis::whereNotNull('resep_obat')->where('status_resep', 'diproses')->get();
        $obats = Obat::all();
        return view('apotek.resep_masuk', compact('resepMasuks', 'obats'));
    }

    // Menampilkan form penjualan obat
    public function jualObat($id) {
        $obat = Obat::findOrFail($id);
        return view('apotek.jual', compact('obat'));
    }

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