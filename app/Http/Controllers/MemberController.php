<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // WAJIB: Untuk menangani keamanan login

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
        // Validasi input
        $request->validate([
            'nik' => 'required',
            'nama_lengkap' => 'required',
        ]);

        Member::create($request->all());
        return redirect('/anggota')->with('success', 'Anggota berhasil didaftarkan!');
    }

    public function edit($id) {
        $member = Member::findOrFail($id);
        return view('member_edit', compact('member'));
    }

    public function update(Request $request, $id) {
        $member = Member::findOrFail($id);
        $member->update($request->all());
        return redirect('/anggota')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id) {
        Member::findOrFail($id)->delete();
        return redirect('/anggota')->with('success', 'Data anggota berhasil dihapus.');
    }

    // PROSES LOGIN NYATA
    public function loginProses(Request $request) {
        // Ambil input username dan password
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek ke database apakah user cocok
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Amankan session
            return redirect()->intended('/dashboard'); // Masuk ke menu utama
        }

        // Jika salah, balik ke login dengan pesan error
        return back()->with('error', 'Username atau Password salah!');
    }

    // PROSES LOGOUT
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Tendang balik ke login
    }
}