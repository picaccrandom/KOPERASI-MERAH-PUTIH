<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PendaftaranKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Obat;
use App\Models\orderObat;
use App\Models\RekamMedis;
use App\Services\AccountingService; 
use App\Models\TransaksiFaskes;
use App\Models\Account;

class KlinikController extends Controller
{
    /**
     * Menampilkan daftar antrian pasien.
     */
    public function index()
    {
        $antrian = PendaftaranKlinik::with(['member', 'transaksiFaskes'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('klinik.index', compact('antrian'));
    }

    public function pendaftaran()
    {
        $members = Member::select('id', 'nik', 'nama_lengkap')->get();
        return view('klinik.pendaftaran', compact('members'));
    }

    /**
     * PROSES DAFTAR PASIEN
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id'   => 'required|exists:members,id',
            'keluhan'     => 'required|min:5|max:255',
            'tensi'       => 'nullable|string|max:20',
        ]);

        try {
            $biayaDaftar = 50000;
            $noReg = 'REG-' . date('YmdHis');

            DB::transaction(function () use ($request, $noReg, $biayaDaftar) {
                $akunKas = Account::where('kode_akun', '1101')->first();
                $akunPendapatan = Account::where('kode_akun', '4102')->first();

                if (!$akunKas || !$akunPendapatan) {
                    throw new \Exception("Akun 1101 atau 4102 belum ada di Master Akun!");
                }

                $pendaftaran = PendaftaranKlinik::create([
                    'no_registrasi' => $noReg,
                    'member_id'     => $request->member_id,
                    'keluhan'       => $request->keluhan,
                    'tensi'         => $request->tensi ?? '-',
                    'biaya_daftar'  => $biayaDaftar,
                    'status'        => 'antri'
                ]);

                AccountingService::catatJurnal($akunKas->id, $biayaDaftar, "PENDAFTARAN: " . $pendaftaran->member->nama_lengkap, 'debit');
                AccountingService::catatJurnal($akunPendapatan->id, $biayaDaftar, "PENDAPATAN DAFTAR: " . $noReg, 'kredit');
            });

            return redirect()->route('klinik.index')->with('success', 'Pasien berhasil didaftarkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Daftar: ' . $e->getMessage())->withInput();
        }
    }

    public function periksa($id)
    {
        $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
        $obats = Obat::where('stok_apotek', '>', 0)->get();
        return view('klinik.emr_input', compact('pasien', 'obats'));
    }

    /**
     * PROSES SIMPAN REKAM MEDIS
     */
    public function simpanTindakan(Request $request, $id) 
    {
        $request->validate([
            'diagnosa' => 'required|min:5',
            'tindakan' => 'required|min:5',
            'biaya_tindakan' => 'required|numeric|min:0',
            'resep_obat.*' => 'nullable|exists:obats,kode_obat',
            'qty.*' => 'nullable|integer|min:1'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $pendaftaran = PendaftaranKlinik::findOrFail($id);
                $member = $pendaftaran->member;

                if (!$member) {
                    throw new \Exception("Data Member tidak ditemukan.");
                }

                $resepNames = [];
                $resepItems = [];
                $nominalObat = 0;

                if ($request->resep_obat) {
                    foreach ($request->resep_obat as $index => $kode) {
                        if ($kode) {
                            $obat = Obat::where('kode_obat', $kode)->first();
                            $qty = $request->qty[$index] ?? 1;
                            
                            $resepNames[] = $obat->nama_obat . " (" . $qty . ")";
                            $resepItems[] = [
                                'kode_obat'  => $obat->kode_obat,
                                'nama_obat'  => $obat->nama_obat,
                                'qty'        => $qty,
                                'harga'      => $obat->harga_jual
                            ];
                            $nominalObat += $obat->harga_jual * $qty;
                        }
                    }
                }

                DB::table('rekam_medis')->insert([
                    'pendaftaran_id' => $id,
                    'diagnosa'       => $request->diagnosa,
                    'tindakan'       => $request->tindakan,
                    'resep_obat'     => count($resepNames) > 0 ? implode(', ', $resepNames) : null,
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);

                TransaksiFaskes::create([
                    'kode_transaksi'   => 'INV-KLK-' . date('YmdHis'),
                    'tanggal'          => now()->toDateString(),
                    'member_id'        => $pendaftaran->member_id,
                    'user_id'          => auth()->id(),
                    'nama'             => $member->nama_lengkap,
                    'COA'              => 'Klinik',
                    'status'           => 'open',
                    'Debit/Credit'     => 'Credit',
                    'Nominal'          => $request->biaya_tindakan,
                    'Keterangan'       => 'Biaya tindakan klinik: ' . $pendaftaran->no_registrasi,
                    'kode_pendaftaran' => $pendaftaran->no_registrasi,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                if (count($resepItems) > 0) {
                    orderObat::create([
                        'pendaftaran_klinik_id' => $id,
                        'tanggal_order'         => now()->toDateString(),
                        'member_id'             => $pendaftaran->member_id,
                        'user_id'               => auth()->id(),
                        'order_body'            => json_encode($resepItems),
                        'nominal'               => $nominalObat,
                        'status'                => 'belum',
                    ]);
                }

                $pendaftaran->update(['status' => 'selesai']);
            });

            return redirect()->route('klinik.index')->with('success', 'Pemeriksaan Selesai & Resep Terkirim!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Simpan EMR: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * PROSES BAYAR & CETAK STRUK (UPDATE!)
     */
    public function bayar($kode_transaksi) {
        try {
            $transaksi = TransaksiFaskes::where('kode_transaksi', $kode_transaksi)->firstOrFail();
            $transaksi->update([
                'status'       => 'closed',
                'updated_at'   => now(),
                'Debit/Credit' => 'Debit'
            ]);

            // Jika request meminta JSON (untuk SweetAlert)
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'kode_transaksi' => $kode_transaksi,
                    'message' => 'Pembayaran Berhasil!'
                ]);
            }

            // Jika request biasa, redirect ke halaman struk
            return redirect()->route('apotek.cetakStruk', $kode_transaksi)
                             ->with('success', 'Pembayaran Berhasil! Silakan Cetak Struk.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Bayar: ' . $e->getMessage());
        }
    }
}