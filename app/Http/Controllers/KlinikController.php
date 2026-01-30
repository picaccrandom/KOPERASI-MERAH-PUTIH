<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PendaftaranKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Models\TransaksiFaskes;

class KlinikController extends Controller
{
    public function index()
    {
        $antrian = PendaftaranKlinik::with('member', 'transaksiFaskes')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('klinik.index', compact('antrian'));
    }

    public function pendaftaran()
    {
        // Dropdown data anggota koperasi
        $members = Member::select('id', 'nik', 'nama_lengkap')->get();
        return view('klinik.pendaftaran', compact('members'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'member_id'         => 'required|exists:members,id',
            'keluhan'           => 'required|min:5|max:255',
            'tensi'             => 'nullable|string|max:20',
            'biaya_daftar'      => 'required'
        ]);

        try {

            // Langsung simpan tanpa DB::beginTransaction atau integrasi kas

            DB::transaction(function () use ($request) {
                // dd($request->all());
                $PendaftaranKlinik = PendaftaranKlinik::create([
                    'no_registrasi' => 'REG-' . date('Ymd') . '-' . random_int(100, 999),
                    'member_id'     => $request->member_id,
                    'keluhan'       => $request->keluhan,
                    'tensi'         => $request->tensi,
                    'biaya_daftar'  => $request->biaya_daftar,
                    'status'        => 'antri'
                ]);

            });



            return redirect()->route('klinik.index')->with('success', 'Pasien berhasil masuk antrian!');

        } catch (\Exception $e) {
            // Jika masih gagal, pesan error ini akan memberitahu masalahnya
            return back()->with('error', 'Gagal Simpan ke Database: ' . $e->getMessage());
        }
    }

    public function bayar($kode_transaksi) {
        $transaksiPendaftaran = TransaksiFaskes::with('member')->where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $transaksiPendaftaran->update(
            ['status' => 'closed',
             'updated_at' => now(),
             'Debit/Credit' => 'Debit'
            ]);

        return redirect()->route('klinik.index')->with('success', 'Pembayaran Rekam Medis Berhasil Dilakukan!');
    }

    // Menampilkan Form Tindakan
    public function periksa($id) {
        $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
        $obats = DB::table('obats')->where('stok_apotek', '>', 0)->get();
        return view('klinik.emr_input', compact('pasien', 'obats'));
    }

    // Proses Simpan EMR
    public function simpanTindakan(Request $request, $id) {
        $request->validate([
            'diagnosa' => 'required|min:5',
            'tindakan' => 'required|min:5',
            'biaya_tindakan' => 'required|numeric|min:0',
            'resep_obat.*' => 'nullable|exists:obats,kode_obat',
            'qty.*' => 'nullable|integer|min:1'
        ]);



        DB::transaction(function () use ($request, $id) {

            $resepobatName = $request->resep_obat ? array_map(function($kode) {
                $obat = Obat::where('kode_obat', $kode)->first();
                return $obat ? $obat->nama_obat : null;
            }, $request->resep_obat) : [];
            $resepobat = array_filter($resepobatName); // Hapus nilai null

            // Simpan Hasil Pemeriksaan
            if ($request->resep_obat) {
                $statusResep = 'diproses';
            }

            DB::table('rekam_medis')->insert([
                'pendaftaran_id' => $id,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'resep_obat' => $resepobat ? implode(', ', $resepobat) : null,
                'created_at' => now()
            ]);

            $kodeTransaksi = 'KLI-' . date('YmdHis');
            $member = Member::find($request->member_id);
            $kodePendaftaran = PendaftaranKlinik::find($id);

            $transaksiKlinik = TransaksiFaskes::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal'        => Carbon::now()->toDateString(),
                'member_id'     => $request->member_id,
                'user_id'       => auth()->user()->id,
                'nama'          => $member->nama_lengkap,
                'COA'           => 'Klinik',
                'status'        => 'open',
                'Debit/Credit'  => 'Credit',
                'Nominal'       => $request->biaya_tindakan,
                'Keterangan'    => 'Biaya tindakan klinik untuk anggota ID: ' . $request->member_id,
                'kode_pendaftaran' => $kodePendaftaran->no_registrasi,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            if($request->resep_obat) {
                $biayaObatTotal = 0;
                // Jika ada resep obat, simpan transaksi apotek juga
                $transaksiApotek = TransaksiFaskes::create([
                    'kode_transaksi' => 'APO-' . date('YmdHis'),
                    'tanggal'        => Carbon::now()->toDateString(),
                    'member_id'     => $request->member_id,
                    'user_id'       => auth()->user()->id,
                    'nama'          => $member->nama_lengkap,
                    'COA'           => 'Apotek',
                    'status'        => 'open',
                    'Debit/Credit'  => 'Credit',
                    'Nominal'       => 0, // Akan dihitung setelah loop resep obat
                    'Keterangan'    => 'Biaya obat untuk anggota ID: ' . $request->member_id,
                    'kode_pendaftaran' => $kodePendaftaran->no_registrasi,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);


                // Simpan Transaksi Obat jika ada resep
                foreach ($request->resep_obat as $index => $kode_obat) {
                    if ($kode_obat) {
                        $obat = Obat::where('kode_obat', $kode_obat)->first();
                        $qty = $request->qty[$index];
                        $biayaObatTotal += $obat->harga_jual * $qty;

                        // Simpan detail resep obat
                        DB::table('transaksi_obat_details')->insert([
                            'kode_transaksi' => $transaksiApotek->kode_transaksi,
                            'obat_id' => $obat->id,
                            'nama_obat' => $obat->nama_obat,
                            'qty' => $qty,
                            'subtotal' => $obat->harga_jual * $qty,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Update total nominal transaksi apotek
                $transaksiApotek->update(['Nominal' => $biayaObatTotal]);
            }

            // Ubah status pasien di antrian dari 'antri' menjadi 'selesai'
            DB::table('pendaftaran_kliniks')->where('id', $id)->update(['status' => 'selesai']);
        });

        return redirect()->route('klinik.index')->with('success', 'Tindakan Medis Berhasil Disimpan!');
    }

    public function show($id)
    {
        // Mengambil pendaftaran beserta relasi rekam medisnya
        $data = PendaftaranKlinik::with(['member', 'rekamMedis'])->findOrFail($id);
        return view('klinik.emr_detail', compact('data'));
    }
}
