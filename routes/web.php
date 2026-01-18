<?php
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SimpanPinjamController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

// Halaman Menu Utama (Layar Bagus)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Redirect halaman depan ke dashboard
Route::get('/', function () { return redirect('/dashboard'); });

// Modul Anggota
Route::get('/anggota', [MemberController::class, 'index']);
Route::get('/tambah', [MemberController::class, 'create']);
Route::post('/simpan', [MemberController::class, 'store']);
Route::get('/edit/{id}', [MemberController::class, 'edit']);
Route::post('/update/{id}', [MemberController::class, 'update']);
Route::get('/hapus/{id}', [MemberController::class, 'destroy']);
Route::get('/login', function() { return view('login'); })->name('login');
Route::post('/login-proses', [MemberController::class, 'loginProses']);
Route::get('/logout', function() { return redirect('/login'); });



Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');

// Simpan Pinjam Routes
Route::prefix('simpanpinjam')->group(function () {
    // Halaman utama
    Route::get('/', function () {
        return view('simpanpinjam');
    })->name('simpanpinjam.index');
    
    // Pinjaman
    Route::get('/pinjaman', function () {
        return view('pinjaman');
    })->name('pinjaman.index');
    
    // Simpanan
    Route::get('/simpanan', function () {
        return view('simpanan');
    })->name('simpanan.index');
    
    // Laporan (opsional)
    Route::get('/laporan', function () {
        return view('laporan');
    })->name('laporan.index');
});

// Dashboard link ke simpan pinjam
Route::get('/dashboard/simpanpinjam', function () {
    return redirect()->route('simpanpinjam.index');
});