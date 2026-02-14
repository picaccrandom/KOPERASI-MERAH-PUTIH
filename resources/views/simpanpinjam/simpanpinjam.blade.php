@extends('layouts.master')

@section('title', 'Dashboard Simpan Pinjam - Koperasi Merah Putih')

@section('content')
    <div class="bg-white/40 backdrop-blur-2xl px-10 py-8 rounded-2xl mx-4 shadow-2xl">
        <!-- BARIS 1: SIMPANAN STATISTICS -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 [&>*]:shadow-lg">
            <!-- Total Simpanan -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Simpanan</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalSimpananOrig, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaSimpanan }} anggota</p>
                    </div>
                    <i class="fas fa-piggy-bank text-2xl text-blue-600"></i>
                </div>
            </div>

            <!-- Simpanan Pokok -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Simpanan Pokok</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalSimpananPokok, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaPokok }} anggota</p>
                    </div>
                    <i class="fas fa-coins text-2xl text-amber-600"></i>
                </div>
            </div>

            <!-- Simpanan Wajib -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Simpanan Wajib</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalSimpananWajib, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaWajib }} anggota</p>
                    </div>
                    <i class="fas fa-hand-holding-hand text-2xl text-green-600"></i>
                </div>
            </div>

            <!-- Simpanan Sukarela -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Simpanan Sukarela</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalSimpananSukarela, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaSukarela }} anggota</p>
                    </div>
                    <i class="fas fa-heart text-2xl text-red-600"></i>
                </div>
            </div>
        </div>

        <!-- BARIS 2: PINJAMAN STATISTICS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 [&>*]:shadow-lg">
            <!-- Pinjaman Belum Bunga -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Pinjaman</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaPinjaman }} anggota</p>
                    </div>
                    <i class="fas fa-hand-holding-dollar text-2xl text-yellow-600"></i>
                </div>
            </div>

            <!-- Pinjaman Aktif -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pinjaman Aktif</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPinjamanAktif, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaPinjamanAktif }} anggota</p>
                    </div>
                    <i class="fas fa-handshake text-2xl text-red-600"></i>
                </div>
            </div>

            <!-- Total Bunga -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pinjaman Jatuh Tempo 7 Hari</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPinjamanUrgent, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaPinjamanUrgent }} anggota</p>
                    </div>
                    <i class="fas fa-clock text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>

        <!-- BARIS 3: BON & MEMBER STATISTICS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 [&>*]:shadow-lg">
            <!-- Total Biaya Bon -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Bon Belum Lunas</p>
                        <p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalBonBelumLunas, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $jumlahAnggotaBon }} anggota</p>
                    </div>
                    <i class="fas fa-receipt text-2xl text-purple-600"></i>
                </div>
            </div>

            <!-- Total Anggota -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Anggota</p>
                        <p class="text-xl font-bold text-gray-800">{{ $totalAnggota }}</p>
                        <p class="text-xs text-gray-600 mt-1">Anggota aktif</p>
                    </div>
                    <i class="fas fa-users text-2xl text-indigo-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 shadow-lg">
            <!-- Recent Pinjaman -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-red-100">
                    <h3 class="text-lg font-bold text-gray-800">Pinjaman Terbaru</h3>
                    <p class="text-sm text-gray-600">5 transaksi terakhir</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($pinjamanTerbaru as $pinjaman)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                        <span class="text-red-600 font-bold text-sm">{{ substr($pinjaman['member']->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $pinjaman['member']->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($pinjaman['tanggal'])->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-600 text-sm">Rp {{ number_format($pinjaman['nominal'], 0, ',', '.') }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $pinjaman['status'] === 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $pinjaman['status'] }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">Belum ada pinjaman</p>
                        @endforelse
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
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <h3 class="text-lg font-bold text-gray-800">Simpanan Terbaru</h3>
                    <p class="text-sm text-gray-600">5 transaksi terakhir</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($simpananTerbaru as $simpanan)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-bold text-sm">{{ substr($simpanan['member']->nama_lengkap, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">{{ $simpanan['member']->nama_lengkap }}</p>
                                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($simpanan['tanggal'])->format('d M Y') }} • {{ ucfirst($simpanan['jenis']) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600 text-sm">Rp {{ number_format($simpanan['nominal'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">Belum ada simpanan</p>
                        @endforelse
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('simpanan.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
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
