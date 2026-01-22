@extends('layouts.master')

@section('title', 'Dashboard Simpan Pinjam - Koperasi Merah Putih')

@section('content')
    <div class="bg-white/40 backdrop-blur-2xl px-10 py-8 rounded-2xl mx-4 shadow-2xl">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 [&>*]:shadow-lg">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Pinjaman Aktif</p>
                        <p class="text-xl font-bold text-gray-800">Rp 1.100.000</p>
                    </div>
                    <i class="fas fa-hand-holding-usd text-2xl text-red-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Simpanan</p>
                        <p class="text-xl font-bold text-gray-800">Rp 650.000</p>
                    </div>
                    <i class="fas fa-piggy-bank text-2xl text-blue-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Angsuran Bulan Ini</p>
                        <p class="text-xl font-bold text-gray-800">Rp 57.000</p>
                    </div>
                    <i class="fas fa-calendar-check text-2xl text-green-600"></i>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Anggota</p>
                        <p class="text-xl font-bold text-gray-800">2</p>
                    </div>
                    <i class="fas fa-users text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 shadow-lg">
            <!-- Recent Pinjaman -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Pinjaman Terbaru</h3>
                    <p class="text-sm text-gray-600">5 transaksi terakhir</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-bold text-sm">P</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Putra Pratama</p>
                                    <p class="text-xs text-gray-600">24 Sep 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">Rp 1.000.000</p>
                                <span class="status-badge status-aktif text-xs">AKTIF</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                    <span class="text-pink-600 font-bold text-sm">S</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Sindi Nur Amelia</p>
                                    <p class="text-xs text-gray-600">23 Sep 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">Rp 100.000</p>
                                <span class="status-badge status-selesai text-xs">LUNAS</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('pinjaman.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            Lihat Semua Pinjaman →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Simpanan -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Simpanan Terbaru</h3>
                    <p class="text-sm text-gray-600">5 transaksi terakhir</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                    <span class="text-pink-600 font-bold text-sm">S</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Sindi Nur Amelia</p>
                                    <p class="text-xs text-gray-600">23 Sep 2024 • Wajib</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">Rp 350.000</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-blue-600 font-bold text-sm">P</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Putra Pratama</p>
                                    <p class="text-xs text-gray-600">24 Sep 2024 • Wajib</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">Rp 150.000</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('simpanan.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            Lihat Semua Simpanan →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('simpan-pinjam-nav').classList.remove('hidden');
    </script>
@endsection
