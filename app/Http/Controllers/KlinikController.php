<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PendaftaranKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Obat;
use App\Models\RekamMedis;
use App\Services\AccountingService; // Import Service Akuntansi

class KlinikController extends Controller
{
    public function index()
    {
        $antrian = PendaftaranKlinik::with('member')
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

    /**
     * Proses Pendaftaran Pasien + Jurnal Otomatis Biaya Daftar
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'keluhan'   => 'required|min:5|max:255',
            'tensi'     => 'nullable|string|max:20'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $biayaDaftar = 50000;
                $noReg = 'REG-' . date('Ymd') . '-' . random_int(100, 999);

                // 1. Simpan ke Database Klinik
                $pendaftaran = PendaftaranKlinik::create([
                    'no_registrasi' => $noReg,
                    'member_id'     => $request->member_id,
                    'keluhan'       => $request->keluhan,
                    'tensi'         => $request->tensi,
                    'biaya_daftar'  => $biayaDaftar,
                    'status'        => 'antri'
                ]);

                /** * 2. INTEGRASI AKUNTANSI: BIAYA PENDAFTARAN
                 * Debit: Kas (1101) | Kredit: Pendapatan Klinik (4102)
                 */
                AccountingService::post(
                    now(), 
                    "Biaya Pendaftaran Klinik: " . ($pendaftaran->member->nama_lengkap ?? 'Pasien'), 
                    $noReg, 
                    $biayaDaftar, 0, 
                    '1101'
                );

                AccountingService::post(
                    now(), 
                    "Pendapatan Pendaftaran (" . $noReg . ")", 
                    $noReg, 
                    0, $biayaDaftar, 
                    '4102'
                );
            });

            return redirect()->route('klinik.index')->with('success', 'Pasien berhasil antri & Biaya pendaftaran terjurnal!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Simpan: ' . $e->getMessage());
        }
    }

    // Menampilkan Form Tindakan
    public function periksa($id) {
        $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
        $obats = DB::table('obats')->where('stok_apotek', '>', 0)->get();
        return view('klinik.emr_input', compact('pasien', 'obats'));
    }

    /**
     * Proses Simpan EMR + Jurnal Otomatis Jasa Medis
     */
    public function simpanTindakan(Request $request, $id) {
        $request->validate([
            'diagnosa' => 'required|min:5',
            'tindakan' => 'required|min:5',
            'resep_obat' => 'nullable|exists:obats,kode_obat',
            'qty' => 'nullable|integer|min:1',
            'total_biaya_tindakan' => 'nullable|numeric' // Asumsi ada input biaya tindakan
        ]);

        DB::transaction(function () use ($request, $id) {
            $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
            
            // Simpan Hasil Pemeriksaan
            $statusResep = $request->resep_obat ? 'diproses' : null;
            
            DB::table('rekam_medis')->insert([
                'pendaftaran_id' => $id,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'resep_obat' => $request->resep_obat,
                'status_resep' => $statusResep,
                'created_at' => now()
            ]);

            // Jika ada biaya tindakan tambahan, jurnal lagi
            if ($request->total_biaya_tindakan > 0) {
                $biaya = (float)$request->total_biaya_tindakan;
                
                AccountingService::post(
                    now(), 
                    "Jasa Medis: " . $pasien->member->nama_lengkap, 
                    $pasien->no_registrasi, 
                    $biaya, 0, 
                    '1101'
                );

                AccountingService::post(
                    now(), 
                    "Pendapatan Jasa Medis (" . $pasien->no_registrasi . ")", 
                    $pasien->no_registrasi, 
                    0, $biaya, 
                    '4102'
                );
            }

            // Ubah status pasien di antrian menjadi 'selesai'
            DB::table('pendaftaran_kliniks')->where('id', $id)->update(['status' => 'selesai']);
        });

        return redirect()->route('klinik.index')->with('success', 'Tindakan Medis Berhasil Disimpan & Terjurnal!');
    }

    public function show($id)
    {
        $data = PendaftaranKlinik::with(['member', 'rekamMedis'])->findOrFail($id);
        return view('klinik.emr_detail', compact('data'));
    }
}