<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Obat;
use App\Models\orderObat;
use App\Models\RekamMedis;
use Illuminate\Http\Request;
use App\Models\TransaksiObat;
use App\Models\TransaksiFaskes;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiObatDetail;
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
        $resepMasuks = orderObat::with('pendaftaranKlinik.member', 'pendaftaranKlinik.rekamMedis')->where('status', 'belum')->orderBy('tanggal_order', 'desc')->get();  
        return view('apotek.index', compact('obats', 'resepMasuks'  )); // Pastikan memanggil view 'index'
    }

    public function resepMasukIndex()
    {
        // Ambil data resep masuk dari klinik (dummy data untuk contoh)
        $resepMasuks = orderObat::with('pendaftaranKlinik.member', 'pendaftaranKlinik.rekamMedis')->where('status', 'belum')->orderBy('tanggal_order', 'desc')->get();
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
            'resep_obat.*' => 'nullable|exists:obats,kode_obat',
            'qty.*' => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {

            $namaMember = Member::find($request->member_id)->nama_lengkap ?? 'member Klinik';
            $noPendaftaran = $request->pendaftaran_klinik_id;
        
           $transaksiFaskes = TransaksiFaskes::create([
                'kode_transaksi' => 'INV-APT-' . date('Ymd') . '-' . rand(1000, 9999),
                'tanggal' => now(),
                'member_id' => $request->member_id ?? null, // Bisa diisi jika ada member terkait
                'user_id' => auth()->id(),
                'nama' => $namaMember,
                'COA' => 'Apotek',
                'status' => 'closed',
                'Nominal' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $totalNominal = 0;
            // Proses resep obat
            foreach ($request->resep_obat as $index => $kodeObat) {
                $obat = Obat::where('kode_obat', $kodeObat)->first();
                $qty = (int)$request->qty[$index];
                if ($obat && $obat->stok_apotek >= $qty) {
                    // Kurangi stok apotek
                    $obat->decrement('stok_apotek', $qty);

                    $subtotal = $obat->harga_jual * $qty;
                    $totalNominal += $subtotal;

                    // Simpan detail transaksi obat
                    TransaksiObatDetail::create([
                        'kode_transaksi' => $transaksiFaskes->kode_transaksi,
                        'obat_id' => $obat->id,
                        'nama_obat' => $obat->nama_obat,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ]);

                    // Update status resep di rekam medis jika ada
                    orderObat::where('pendaftaran_klinik_id', $noPendaftaran)
                        ->where('status', 'belum')
                        ->update(['status' => 'selesai']);
                } else {
                    throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi.");
                }
            }
            

            // Update total nominal transaksi
            $transaksiFaskes->update(['Nominal' => $totalNominal]);

        });
        return redirect()->route('apotek.resep')->with('success', 'Pembayaran resep berhasil dilakukan!');
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
            TransaksiFaskes::create([
                'kode_transaksi' => $noInvoice,
                'tanggal'        => now()->toDateString(),
                'member_id'     => null, // Penjualan umum
                'user_id'       => auth()->id(),
                'nama'          => 'member OTS',
                'COA'           => 'Apotek',
                'status'        => 'closed',
                'Debit/Credit'  => 'Debit',
                'Nominal'       => $totalBayar,
                'Keterangan'    => 'Penjualan obat: ' . $obat->nama_obat,
                'kode_pendaftaran' => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            TransaksiObatDetail::create([
                'kode_transaksi' => $noInvoice,
                'obat_id' => $obat->id,
                'nama_obat' => $obat->nama_obat,
                'qty' => $request->qty,
                'subtotal' => $totalBayar,
            ]);


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

    public function prosesPembayaranCart(Request $request) {
        $cartData = json_decode($request->cart_data, true);
        if (!$cartData || !is_array($cartData)) {
            return back()->with('error', 'Data keranjang tidak valid.');
        }

        // Generate kode transaksi unik
        $kode_transaksi = 'INV-APT-' . date('Ymd') . '-' . rand(1000, 9999);

        // Simpan transaksi faskes
        TransaksiFaskes::create([
            'kode_transaksi' => $kode_transaksi,
            'tanggal' => now(),
            'member_id' => null, // Bisa diisi jika ada member terkait
            'user_id' => auth()->id(),
            'nama' => 'member OTS',
            'COA' => 'Apotek',
            'status' => 'open',
            'Nominal' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($cartData as $item) {
            $obat = Obat::findOrFail($item['id']);
            $qty = (int)$item['qty'];

            if ($obat->stok_apotek >= $qty) {
                // Kurangi stok apotek
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

        return redirect()->route('apotek.index')->with('success', "Pembayaran keranjang berhasil dilakukan! Kode Transaksi: {$kode_transaksi}");
    }

    function hapusResep($kode_transaksi) {
        $data = orderObat::where('kode_transaksi', $kode_transaksi)->first();
        if (!$data) {
            return redirect()->route('apotek.resep')->with('error', 'Data resep tidak ditemukan.');
        }
        $data->delete();
        
        return redirect()->route('apotek.resep')->with('success', 'Resep berhasil dihapus.');
    }
}