<?php
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;

// 1. Halaman Login
Route::get('/login', function() { 
    return view('login'); 
})->name('login');

Route::post('/login-proses', [MemberController::class, 'loginProses']);
Route::get('/logout', [MemberController::class, 'logout']);

// 2. Middleware Auth
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () { 
        return redirect('/dashboard'); 
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Modul Anggota
    Route::get('/anggota', [MemberController::class, 'index']);
    Route::get('/tambah', [MemberController::class, 'create']);
    Route::post('/simpan', [MemberController::class, 'store']);
    Route::get('/edit/{id}', [MemberController::class, 'edit']);
    Route::post('/update/{id}', [MemberController::class, 'update']);
    Route::get('/hapus/{id}', [MemberController::class, 'destroy']);

    // Modul Admin & Master User
    Route::get('/admin', function () {
        return view('admin_index');
    });

    // Route Master User (CRUD)
    Route::get('/admin/master-user', [UserController::class, 'index'])->name('user.index');
    Route::post('/admin/master-user', [UserController::class, 'store'])->name('user.store');
    Route::put('/admin/master-user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/master-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Route Ganti Password
    Route::get('/admin/ganti-password', [UserController::class, 'gantiPassword'])->name('password.ganti');
    Route::post('/admin/ganti-password', [UserController::class, 'updatePassword'])->name('password.update.proses');

    // Modul Gudang (Master Barang) 
    Route::get('/admin/gudang', [BarangController::class, 'index'])->name('gudang.index');
    Route::post('/admin/gudang', [BarangController::class, 'store'])->name('gudang.store');
    Route::put('/admin/gudang/{id}', [BarangController::class, 'update'])->name('gudang.update');
    Route::delete('/admin/gudang/{id}', [BarangController::class, 'destroy'])->name('gudang.destroy');

    // Route Master Barang (yang sebelumnya Anda buat)
    Route::get('/admin/gudang', [BarangController::class, 'index'])->name('gudang.index');

    // Route Stok Masuk
    Route::post('/admin/gudang/stok-masuk', [BarangController::class, 'storeStokMasuk'])->name('stok.masuk.store');

    // Route Modul Kasir
    Route::get('/admin/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/admin/kasir/proses', [KasirController::class, 'store'])->name('kasir.store');

    // // Halaman utama
    Route::get('/', function () {
        return view('admin.simpanpinjam');
    })->name('simpanpinjam.index');
    
    // Pinjaman
    Route::get('/pinjaman', function () {
        return view('admin.pinjaman');
    })->name('pinjaman.index');
    
    // Simpanan
    Route::get('/simpanan', function () {
        return view('admin.simpanan');
    })->name('simpanan.index');
    
    // Laporan (opsional)
    Route::get('/laporan', function () {
        return view('admin.laporan');
    })->name('laporan.index');

    // Dashboard link ke simpan pinjam
    Route::get('/simpanpinjam', function () {
    return redirect()->route('simpanpinjam.index');
    });
});