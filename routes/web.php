<?php
use App\Http\Controllers\MemberController;
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