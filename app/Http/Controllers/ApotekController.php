<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\TransaksiFaskes;
use App\Models\TransaksiObat;
use App\Models\TransaksiObatDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $resepMasuks = TransaksiFaskes::with('member', 'pendaftaranKlinik.rekamMedis')
            ->where('COA', 'Apotek')
            ->where('status', 'open')
            ->get();
        $obats = Obat::all();
        $rekamMedis = RekamMedis::with('pendaftaranKlinik.member')->get();
        return view('apotek.resep_masuk', compact('resepMasuks', 'obats', 'rekamMedis'));
    }

    // Menampilkan form penjualan obat
    public function jualObat($id) {
        $obat = Obat::findOrFail($id);
        return view('apotek.jual', compact('obat'));
    }

    public function bayarOrder(Request $request, $kode_transaksi) {
        $request->validate([
            'kode_transaksi' => 'required|exists:transaksi_faskes,kode_transaksi',
        ]);

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

        DB::transaction(function () use ($obat, $request) {
            RekamMedis::where('resep_obat', $obat->kode_obat)->update(['status_resep' => 'selesai']);

            // Kurangi stok retail di apotek
            $obat->decrement('stok_apotek', $request->qty);

        });

        return redirect()->route('apotek.index')->with('success', 'Obat berhasil terjual!');
    }
}
