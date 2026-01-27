<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\KreditAnggota;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($request) {
            Member::create($request->all());
            // KreditAnggota::create([
            //     'member_id' => Member::latest()->first()->id,
            //     'limit' => 1000000,
            // ]);

            KreditAnggota::create([
                'member_id' => Member::latest()->first()->id,
                'limit' => 5000000,
            ]);
        });

        // catat log tambah anggota

        
        writeLog(
            'Master Anggota',
            'Create',
            'members',
            Member::latest()->first()->id ?? null,
            null,
            json_encode($request->all()),
            'Menambahkan anggota baru: ' . $request->nama_lengkap,
            'info',
            'success'
        );
        
        return redirect('/anggota')->with('success', 'Anggota berhasil didaftarkan!');
    }

    public function edit($id) {
        $member = Member::findOrFail($id);

        return view('member_edit', compact('member'));
    }

    public function update(Request $request, $id) {
        $member = Member::findOrFail($id);
        $memberOldData = $member->toArray(); // Simpan data lama sebelum diupdate
        $member->update($request->all());

        // catat log update anggota
        writeLog(
            'Master Anggota',
            'Update',
            'members',
            $member->id ?? null,
            json_encode($memberOldData),
            json_encode($request->all()),
            'Memperbarui data anggota: ' . $member->nama_lengkap,
            'info',
            'success'
        );
        
        return redirect('/anggota')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id) {
        Member::findOrFail($id)->delete();

        // catat log hapus anggota
        writeLog(
            'Master Anggota',
            'Delete',
            'members',
            $id ?? null,
            null,
            null,
            'Menghapus data anggota dengan ID: ' . $id, 
            'info',
            'success'
        );
        
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

            // catat log login user
            writeLog(
                'Authentication',
                'Login',
                'users',
                Auth::id() ?? null,
                null,
                null,
                'User ' . Auth::user()->username . ' berhasil login',
                'info',
                'success'
            );
            
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

        // catat log logout user
        writeLog(
            'Authentication',
            'Logout',
            'users',
            Auth::id() ?? null,
            null,
            null,
            'User ' . (Auth::user()->username ?? 'Unknown') . ' berhasil logout',
            'info', 
            'success'
        );
        
        return redirect('/login'); // Tendang balik ke login
    }
}