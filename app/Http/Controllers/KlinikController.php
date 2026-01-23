<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PendaftaranKlinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'keluhan'   => 'required|min:5|max:255',
            'tensi'     => 'nullable|string|max:20'
        ]);

        try {
            // Langsung simpan tanpa DB::beginTransaction atau integrasi kas
            PendaftaranKlinik::create([
                'no_registrasi' => 'REG-' . date('Ymd') . '-' . random_int(100, 999),
                'member_id'     => $request->member_id,
                'keluhan'       => $request->keluhan,
                'tensi'         => $request->tensi,
                'biaya_daftar'  => 50000,
                'status'        => 'antri'
            ]);

            return redirect()->route('klinik.index')->with('success', 'Pasien berhasil masuk antrian!');

        } catch (\Exception $e) {
            // Jika masih gagal, pesan error ini akan memberitahu masalahnya
            return back()->with('error', 'Gagal Simpan ke Database: ' . $e->getMessage());
        }
    }

    // Menampilkan Form Tindakan
    public function periksa($id) {
        $pasien = PendaftaranKlinik::with('member')->findOrFail($id);
        return view('klinik.emr_input', compact('pasien'));
    }

    // Proses Simpan EMR
    public function simpanTindakan(Request $request, $id) {
        $request->validate([
            'diagnosa' => 'required|min:5',
            'tindakan' => 'required|min:5'
        ]);

        DB::transaction(function () use ($request, $id) {
            // Simpan Hasil Pemeriksaan
            DB::table('rekam_medis')->insert([
                'pendaftaran_id' => $id,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'created_at' => now()
            ]);

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