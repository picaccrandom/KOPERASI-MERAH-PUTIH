<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PendaftaranKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Services\AccountingService; 
use App\Models\TransaksiFaskes;
use App\Models\Account; // Pastikan Model Account diimport

class KlinikController extends Controller
{
    /**
     * Menampilkan daftar antrian pasien.
     * Saya hapus filter tanggal agar 120 row lama Mas tetap muncul semua.
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
     * Solusi Error 1452: Mencari ID Akun berdasarkan Kode Akun
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
                
                // 1. CARI ID ASLI DARI KODE AKUN (PENTING!)
                // Kita cari row di tabel accounts yang kode_akun-nya '1101' dan '4102'
                $akunKas = Account::where('kode_akun', '1101')->first();
                $akunPendapatan = Account::where('kode_akun', '4102')->first();

                // Cek apakah akunnya ada di database Mas
                if (!$akunKas || !$akunPendapatan) {
                    throw new \Exception("Gagal Jurnal: Kode Akun 1101 (Kas) atau 4102 (Pendapatan) tidak ditemukan di Master Akun. Silakan buat dulu di menu Akuntansi.");
                }

                // 2. Simpan Pendaftaran
                $pendaftaran = PendaftaranKlinik::create([
                    'no_registrasi' => $noReg,
                    'member_id'     => $request->member_id,
                    'keluhan'       => $request->keluhan,
                    'tensi'         => $request->tensi ?? '-',
                    'biaya_daftar'  => $biayaDaftar,
                    'status'        => 'antri'
                ]);

                // 3. Catat Jurnal menggunakan ID (Bukan Kode)
                // Kita masukkan $akunKas->id, bukan angka 1101
                AccountingService::catatJurnal($akunKas->id, $biayaDaftar, "PENDAFTARAN PASIEN: " . $pendaftaran->member->nama_lengkap, 'debit');
                AccountingService::catatJurnal($akunPendapatan->id, $biayaDaftar, "PENDAPATAN PENDAFTARAN KLINIK", 'kredit');
            });

            return redirect()->route('klinik.index')->with('success', 'Pasien berhasil didaftarkan ke antrian!');

        } catch (\Exception $e) {
            // Jika gagal, Mas akan melihat pesan error spesifik di layar (Bukan sekedar berkedip)
            return back()->with('error', 'Gagal Simpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * PROSES SIMPAN TINDAKAN (REKAM MEDIS)
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
                $member = Member::find($pendaftaran->member_id);

                $resepItems = [];
                $resepNarasi = [];

                if ($request->resep_obat) {
                    foreach ($request->resep_obat as $index => $kode_obat) {
                        if ($kode_obat) {
                            $obat = Obat::where('kode_obat', $kode_obat)->first();
                            $qty = $request->qty[$index];
                            $resepNarasi[] = $obat->nama_obat . " (" . $qty . ")";
                            
                            $resepItems[] = [
                                'obat_id'    => $obat->id,
                                'kode_obat'  => $obat->kode_obat,
                                'nama_obat'  => $obat->nama_obat,
                                'qty'        => $qty,
                                'harga_jual' => $obat->harga_jual,
                                'subtotal'   => $obat->harga_jual * $qty
                            ];
                        }
                    }
                }

                // Simpan Rekam Medis
                DB::table('rekam_medis')->insert([
                    'pendaftaran_id' => $id,
                    'diagnosa'       => $request->diagnosa,
                    'tindakan'       => $request->tindakan,
                    'resep_obat'     => count($resepNarasi) > 0 ? implode(', ', $resepNarasi) : null,
                    'order_data'     => count($resepItems) > 0 ? json_encode($resepItems) : null,
                    'status_resep'   => count($resepItems) > 0 ? 'pending' : 'none',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);

                // Buat Transaksi Faskes (Jasa Medis)
                TransaksiFaskes::create([
                    'kode_transaksi'   => 'INV-KLK-' . date('YmdHis'),
                    'tanggal'          => now()->toDateString(),
                    'member_id'        => $pendaftaran->member_id,
                    'user_id'          => auth()->user()->id,
                    'nama'             => $member->nama_lengkap,
                    'COA'              => 'Klinik',
                    'status'           => 'open',
                    'Debit/Credit'     => 'Credit',
                    'Nominal'          => $request->biaya_tindakan,
                    'Keterangan'       => 'Jasa Medis: ' . $pendaftaran->no_registrasi,
                    'kode_pendaftaran' => $pendaftaran->no_registrasi,
                    'created_at'       => now(),
                ]);

                $pendaftaran->update(['status' => 'selesai']);
            });

            return redirect()->route('klinik.index')->with('success', 'Rekam Medis Berhasil Disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Simpan Tindakan: ' . $e->getMessage());
        }
    }

    public function periksa($id)
    {
        $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
        $obats = Obat::where('stok_apotek', '>', 0)->get();
        return view('klinik.emr_input', compact('pasien', 'obats'));
    }

    public function show($id)
    {
        $data = PendaftaranKlinik::with(['member', 'rekamMedis'])->findOrFail($id);
        return view('klinik.emr_detail', compact('data'));
    }
}