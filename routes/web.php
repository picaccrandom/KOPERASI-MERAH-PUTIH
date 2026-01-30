<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SimpanPinjamController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\KlinikController;
use App\Http\Controllers\ApotekController;
use App\Http\Controllers\DistribusiController;


// 1. Halaman Login
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/login-proses', [MemberController::class, 'loginProses']);
Route::get('/logout', [MemberController::class, 'logout'])->name('logout');

// 2. Middleware Auth
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect('/dashboard');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Modul Anggota
    Route::get('/anggota', [MemberController::class, 'index'])->name('member.index');
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

    // Route Stok Masuk
    Route::post('/admin/gudang/stok-masuk', [BarangController::class, 'storeStokMasuk'])->name('stok.masuk.store');

    // Route Modul Kasir
    Route::get('/admin/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/admin/kasir/proses', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/admin/kasir/struk/{kode_transaksi}', [KasirController::class, 'cetakStruk'])->name('kasir.struk');


    // Modul Simpan Pinjam
    Route::get('/simpanpinjam', [SimpanPinjamController::class, 'index'])->name('simpanpinjam.index'); // Dashboard Simpan Pinjam
    // Route::get('/simpanpinjam', [SimpanPinjamController::class, 'simpanpinjam'])->name('simpanpinjam.index'); // Simpan Pinjam

    // dashboard simpan pinjam

    // Pinjaman Routes
    Route::get('/pinjaman/dashboard', [PinjamanController::class, 'dashboardSP'])->name('pinjaman.dashboardSP'); // Pinjaman

    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index'); // Pinjaman
    Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create'); // Pinjaman
    Route::post('/pinjaman/create', [PinjamanController::class, 'store'])->name('pinjaman.store'); // Pinjaman
    Route::get('/pinjaman/detail/{no_transaksi_sp}', [PinjamanController::class, 'detail'])->name('pinjaman.detail'); // Detail Pinjaman
    Route::post('/pinjaman/bayar-angsuran/{memberId}/{id_angsuran}', [PinjamanController::class, 'bayarAngsuran'])->name('pinjaman.bayarAngsuran'); // Pinjaman
    Route::delete('/pinjaman/{no_transaksi_sp}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');

    // bon Routes
    Route::get('/bon', [PinjamanController::class, 'indexBon'])->name('bon.indexBon'); // Pinjaman
    Route::get('/bon/detail/{no_transaksi_sp}', [PinjamanController::class, 'detailBon'])->name('bon.detailBon'); // Detail Bon
    Route::post('/bon/bayarBon/{no_transaksi_sp}', [PinjamanController::class, 'bayarBon'])->name('bon.bayarBon'); // Lunasi Bon
    Route::delete('/bon/hapusBon/{no_transaksi_sp}', [PinjamanController::class, 'destroy'])->name('bon.destroy'); // Lunasi Bon


    // Simpanan Routes
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index'); // Simpanan
    Route::get('/simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create'); // Simpanan tampil form tambah
    Route::post('/simpanan/create', [SimpananController::class, 'store'])->name('simpanan.store'); // Simpanan tambah
    Route::get('/simpanan/tarik', [SimpananController::class, 'createTarik'])->name('simpanan.tarik'); // Simpanan tampil form tarik
    Route::post('/simpanan/tarik', [SimpananController::class, 'reduce'])->name('simpanan.reduce'); // Simpanan tampil form tarik
    Route::get('/simpanan/show/{id}', [SimpananController::class, 'show'])->name('simpanan.show');
    Route::get('/simpanan/{id}/edit', [SimpananController::class, 'edit'])->name('simpanan.edit');
    Route::put('/simpanan/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
    Route::delete('/simpanan/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');


    Route::get('/laporan', [PinjamanController::class, 'laporanIndex'])->name('laporan.index'); // Laporan

    //Klinik
    Route::prefix('klinik')->group(function () {
        Route::get('/', [KlinikController::class, 'index'])->name('klinik.index');
        Route::get('/pendaftaran', [KlinikController::class, 'pendaftaran'])->name('klinik.pendaftaran');
        Route::post('/simpan-pemeriksaan', [KlinikController::class, 'store'])->name('klinik.store');
        Route::get('/bayar/{kode_transaksi}', [KlinikController::class, 'bayar'])->name('klinik.bayar');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/klinik', [KlinikController::class, 'index'])->name('klinik.index');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/klinik/periksa/{id}', [KlinikController::class, 'periksa'])->name('klinik.periksa');
        Route::post('/klinik/simpan-tindakan/{id}', [KlinikController::class, 'simpanTindakan'])->name('klinik.simpanTindakan');
    });

    Route::get('/klinik/detail/{id}', [KlinikController::class, 'show'])->name('klinik.show');

    Route::get('/apotek-retail', [ApotekController::class, 'apotekIndex'])->name('apotek.index');

    Route::get('/gudang-apotek', [ApotekController::class, 'gudangIndex'])->name('apotek.gudang');
    Route::post('/gudang/store', [ApotekController::class, 'storeObat'])->name('apotek.store');
    Route::post('/gudang/mutasi/{id}', [ApotekController::class, 'kirimKeApotek'])->name('apotek.kirim');

    Route::get('/apotek/jual/{id}', [ApotekController::class, 'jualObat'])->name('apotek.formJual');
    Route::post('/apotek/proses-jual/{id}', [ApotekController::class, 'prosesJual'])->name('apotek.prosesJual');
    Route::get('/apotek/resep-masuk', [ApotekController::class, 'resepMasukIndex'])->name('apotek.resep');
    Route::post('/apotek/bayar-order/{kode_transaksi}', [ApotekController::class, 'bayarOrder'])->name('apotek.bayarOrder');

    Route::prefix('gudang-distribusi')->group(function () {
        Route::get('/', [DistribusiController::class, 'index'])->name('distribusi.gudang');
        Route::post('/store', [DistribusiController::class, 'store'])->name('distribusi.store');
        Route::post('/kirim-luar/{id}', [DistribusiController::class, 'kirimLuarDesa'])->name('distribusi.kirimLuar');
        Route::post('/mutasi-gerai/{id}', [DistribusiController::class, 'mutasiKeGerai'])->name('distribusi.mutasi');
    });

    Route::post('/gudang-distribusi/kirim-luar/{id}', [DistribusiController::class, 'prosesFaktur'])->name('distribusi.prosesFaktur');
    Route::post('/gudang-distribusi/mutasi-gerai/{id}', [DistribusiController::class, 'prosesMutasi'])->name('distribusi.prosesMutasi');

});
