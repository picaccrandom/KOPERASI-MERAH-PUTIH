<?php

namespace App\Http\Controllers;

use App\Models\Member; // Baris ini yang tadi hilang sehingga muncul error
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index() {
        $members = Member::all();
        return view('member_index', compact('members'));
    }

    public function create() {
        return view('member_create');
    }

    public function store(Request $request) {
        // Validasi sederhana agar data tidak kosong
        $request->validate([
            'nik' => 'required',
            'nama_lengkap' => 'required',
        ]);

        Member::create($request->all());
        return redirect('/')->with('success', 'Anggota berhasil didaftarkan!');
    }

        // Munculkan form edit
    public function edit($id) {
        $member = Member::findOrFail($id);
        return view('member_edit', compact('member'));
    }

    // Proses update data
    public function update(Request $request, $id) {
        $member = Member::findOrFail($id);
        $member->update($request->all());
        return redirect('/')->with('success', 'Data berhasil diperbarui!');
    }

    // Proses hapus data
    public function destroy($id) {
        Member::findOrFail($id)->delete();
        return redirect('/anggota')->with('success', 'Data anggota berhasil dihapus dari sistem.');
    }

    public function loginProses(Request $request) {
        // Logika login sederhana untuk simulasi
        if($request->username == 'yoga' && $request->password == 'admin') {
            return redirect('/dashboard');
        }
        return back()->with('error', 'Akses Ditolak! Akun IT tidak ditemukan.');
    }
}