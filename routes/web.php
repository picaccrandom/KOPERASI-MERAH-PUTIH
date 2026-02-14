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
use App\Http\Controllers\KantorKoperasi\AccountingController;
use App\Http\Controllers\KantorKoperasi\KeuanganController;

// ---------------------------------------------------------
// 1. HALAMAN LOGIN & LOGOUT
// ---------------------------------------------------------
Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/login-proses', [MemberController::class, 'loginProses']);
Route::get('/logout', [MemberController::class, 'logout'])->name('logout');

// ---------------------------------------------------------
// 2. MIDDLEWARE AUTH (SEMUA MENU DI DALAM SINI)
// ---------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect('/dashboard');
    })->name('home');

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // --- MODUL ANGGOTA ---
    Route::get('/anggota', [MemberController::class, 'index'])->name('member.index');
    Route::get('/tambah', [MemberController::class, 'create']);
    Route::post('/simpan', [MemberController::class, 'store']);
    Route::get('/edit/{id}', [MemberController::class, 'edit']);
    Route::post('/update/{id}', [MemberController::class, 'update']);
    Route::get('/hapus/{id}', [MemberController::class, 'destroy']);
    Route::get('/member/show/{modul}/{id}', [MemberController::class, 'show'])->name('member.show');
    Route::get('/member/banned/{id}', [MemberController::class, 'banMember'])->name('member.banned');

    // --- MODUL ADMIN & MASTER USER ---
    Route::get('/admin', function () { return view('admin_index'); });
    Route::get('/admin/master-user', [UserController::class, 'index'])->name('user.index');
    Route::post('/admin/master-user', [UserController::class, 'store'])->name('user.store');
    Route::put('/admin/master-user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/admin/master-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::get('/admin/ganti-password', [UserController::class, 'gantiPassword'])->name('password.ganti');
    Route::post('/admin/ganti-password', [UserController::class, 'updatePassword'])->name('password.update.proses');

    // --- MODUL GUDANG (MASTER BARANG) ---
    Route::get('/admin/gudang', [BarangController::class, 'index'])->name('gudang.index');
    Route::post('/admin/gudang', [BarangController::class, 'store'])->name('gudang.store');
    Route::put('/admin/gudang/{id}', [BarangController::class, 'update'])->name('gudang.update');
    Route::delete('/admin/gudang/{id}', [BarangController::class, 'destroy'])->name('gudang.destroy');
    Route::post('/admin/gudang/stok-masuk', [BarangController::class, 'storeStokMasuk'])->name('stok.masuk.store');

    // --- MODUL KASIR ---
    Route::get('/admin/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/admin/kasir/proses', [KasirController::class, 'store'])->name('kasir.store');
    Route::get('/admin/kasir/struk/{kode_transaksi}/{kembalian}', [KasirController::class, 'cetakStruk'])->name('kasir.struk');

    // --- MODUL SIMPAN PINJAM & PINJAMAN ---
    Route::get('/simpanpinjam', [SimpanPinjamController::class, 'index'])->name('simpanpinjam.index');
    Route::get('/simpanpinjam/api/statistics', [SimpanPinjamController::class, 'getStatistics'])->name('simpanpinjam.statistics');
    Route::get('/pinjaman/dashboard', [PinjamanController::class, 'dashboardSP'])->name('pinjaman.dashboardSP');
    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
    Route::post('/pinjaman/create', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::get('/pinjaman/detail/{no_transaksi_sp}', [PinjamanController::class, 'detail'])->name('pinjaman.detail');
    Route::post('/pinjaman/bayar-angsuran/{memberId}/{id_angsuran}', [PinjamanController::class, 'bayarAngsuran'])->name('pinjaman.bayarAngsuran');
    Route::delete('/pinjaman/{no_transaksi_sp}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
    Route::get('/pinjaman/struk-pinjaman/{modul}/{no_transaksi_sp}/{id_angsuran}', [PinjamanController::class, 'cetakStrukAngsuran'])->name('pinjaman.strukAngsuran');
    Route::get('/pinjaman/struk-pinjaman/{modul}/{no_transaksi_sp}', [PinjamanController::class, 'cetakStrukPinjaman'])->name('pinjaman.strukPinjaman');

    // --- MODUL BON ---
    Route::get('/bon', [PinjamanController::class, 'indexBon'])->name('bon.indexBon');
    Route::get('/bon/detail/{no_transaksi_sp}', [PinjamanController::class, 'detailBon'])->name('bon.detailBon');
    Route::post('/bon/bayarBon/{no_transaksi_sp}', [PinjamanController::class, 'bayarBon'])->name('bon.bayarBon');
    Route::delete('/bon/hapusBon/{no_transaksi_sp}', [PinjamanController::class, 'destroy'])->name('bon.destroy');

    // --- MODUL SIMPANAN ---
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('/simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create');
    Route::post('/simpanan/create', [SimpananController::class, 'store'])->name('simpanan.store');
    Route::get('/simpanan/tarik', [SimpananController::class, 'createTarik'])->name('simpanan.tarik');
    Route::post('/simpanan/tarik', [SimpananController::class, 'reduce'])->name('simpanan.reduce');
    Route::get('/simpanan/show/{id}', [SimpananController::class, 'show'])->name('simpanan.show');
    Route::get('/simpanan/{id}/edit', [SimpananController::class, 'edit'])->name('simpanan.edit');
    Route::put('/simpanan/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
    Route::delete('/simpanan/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');
    Route::get('/simpanan/struk-simpan/{modul}/{no_transaksi_sp}', [SimpananController::class, 'strukSimpan'])->name('simpanan.strukSimpan');

    // --- MODUL KLINIK ---
    Route::prefix('klinik')->group(function () {
        Route::get('/', [KlinikController::class, 'index'])->name('klinik.index');
        Route::get('/pendaftaran', [KlinikController::class, 'pendaftaran'])->name('klinik.pendaftaran');
        Route::post('/simpan-pemeriksaan', [KlinikController::class, 'store'])->name('klinik.store');
        Route::get('/bayar/{kode_transaksi}', [KlinikController::class, 'bayar'])->name('klinik.bayar');
        Route::get('/periksa/{id}', [KlinikController::class, 'periksa'])->name('klinik.periksa');
        Route::post('/simpan-tindakan/{id}', [KlinikController::class, 'simpanTindakan'])->name('klinik.simpanTindakan');
        Route::get('/detail/{id}', [KlinikController::class, 'show'])->name('klinik.show');
    });

    // --- MODUL APOTEK & GUDANG APOTEK ---
    Route::get('/apotek-retail', [ApotekController::class, 'apotekIndex'])->name('apotek.index');
    Route::get('/gudang-apotek', [ApotekController::class, 'gudangIndex'])->name('apotek.gudang');
    Route::post('/gudang/store', [ApotekController::class, 'storeObat'])->name('apotek.store');
    Route::post('/gudang/mutasi/{id}', [ApotekController::class, 'kirimKeApotek'])->name('apotek.kirim');
    Route::get('/apotek/jual/{id}', [ApotekController::class, 'jualObat'])->name('apotek.formJual');
    Route::post('/apotek/proses-jual/{id}', [ApotekController::class, 'prosesJual'])->name('apotek.prosesJual');
    Route::get('/apotek/resep-masuk', [ApotekController::class, 'resepMasukIndex'])->name('apotek.resep');
    Route::post('/apotek/bayar-order/', [ApotekController::class, 'bayarOrder'])->name('apotek.bayarOrder');
    Route::post('/apotek/proses-pembayaran-cart', [ApotekController::class, 'prosesPembayaranCart'])->name('apotek.proses-pembayaran-cart');
    Route::delete('/apotek/hapus-resep/{kode_transaksi}', [ApotekController::class, 'hapusResep'])->name('apotek.hapusResep');
    
    // RUTE BARU: Tarik Resep dari Klinik ke Apotek
    Route::post('/apotek/tarik-resep', [ApotekController::class, 'tarikResep'])->name('apotek.tarik_resep');

    // --- MODUL DISTRIBUSI ---
    Route::prefix('gudang-distribusi')->group(function () {
        Route::get('/', [DistribusiController::class, 'index'])->name('distribusi.gudang');
        Route::post('/store', [DistribusiController::class, 'store'])->name('distribusi.store');
        Route::post('/kirim-luar/{id}', [DistribusiController::class, 'prosesFaktur'])->name('distribusi.prosesFaktur');
        Route::post('/mutasi-gerai/{id}', [DistribusiController::class, 'prosesMutasi'])->name('distribusi.prosesMutasi');
    });

    // ---------------------------------------------------------
    // 3. MODUL KANTOR KOPERASI (PUSAT KENDALI TERPADU)
    // ---------------------------------------------------------
    Route::prefix('kantor-koperasi')->group(function () {
        
        // Dashboard Statistik & Visualisasi
        Route::get('/dashboard-statistik', [AccountingController::class, 'dashboardStatistik'])->name('kantor.dashboard_statistik');

        // Akuntansi & Buku Besar
        Route::get('/akuntansi', [AccountingController::class, 'index'])->name('akuntansi.index');
        Route::get('/akuntansi/ledger/{id}', [AccountingController::class, 'showLedger'])->name('akuntansi.ledger');
        Route::post('/akuntansi/store-account', [AccountingController::class, 'store'])->name('akuntansi.store_account');

        // Arus Kas / Pengeluaran
        Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::post('/keuangan/store', [KeuanganController::class, 'store'])->name('keuangan.store');

        // Pusat Laporan Terpadu
        Route::get('/pusat-laporan', [KasirController::class, 'pusatLaporan'])->name('kantor.laporan.index');
        
        // Link Laporan Lama
        Route::get('/akuntansi/laba-rugi', [AccountingController::class, 'showLabaRugi'])->name('akuntansi.labarugi');
        Route::get('/akuntansi/neraca', [AccountingController::class, 'showNeraca'])->name('akuntansi.neraca');
    });

    // Rute Laporan Pinjaman
    Route::get('/laporan-pinjaman', [PinjamanController::class, 'laporanIndex'])->name('laporan.index');

    Route::get('/apotek/histori', [ApotekController::class, 'historiPenjualan'])->name('apotek.histori');
    Route::get('/apotek/histori/{kode_transaksi}', [ApotekController::class, 'detailHistori'])->name('apotek.histori.detail');

    Route::get('/cetak-struk/{kode_transaksi}', [ApotekController::class, 'cetakStruk'])->name('cetak.struk');
});