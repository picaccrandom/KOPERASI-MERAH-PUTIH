<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('admin.master_user', compact('users'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$id,
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return back()->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // Fungsi Ganti Password
    public function gantiPassword() {
        return view('admin.ganti_password');
    }

    public function updatePassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password berhasil diubah!');
    }
}