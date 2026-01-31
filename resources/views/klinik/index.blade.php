@extends('layouts.master')
@section('content')
    
    {{-- Background menggunakan asset yang sudah kita sepakati --}}
    <div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-klinik.png') }}');">
        <div class="bg-blue-900/20 backdrop-blur-md min-h-screen p-10">

            {{-- Header Modul Klinik --}}
            <div class="flex justify-between items-end mb-10 border-l-8 border-blue-600 pl-6">
                <div>
                    <h1 class="text-5xl font-black text-slate-800 uppercase tracking-tighter">
                        Layanan <span class="bg-blue-600 text-white px-3 rounded-lg shadow-lg">Klinik Desa</span>
                    </h1>
                    <p class="text-blue-900 font-bold mt-2 italic">Sistem Informasi Medis Terintegrasi Nangsri</p>
                </div>
                <a href="{{ route('klinik.pendaftaran') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-xl"></i> Daftar Pasien Baru
                </a>
            </div>

            {{-- Container Tabel Antrian --}}
            <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-white/50">
                <div
                    class="bg-blue-600 px-8 py-4 flex justify-between items-center text-white font-black uppercase tracking-widest">
                    <span><i class="fas fa-notes-medical mr-2"></i> Antrian Pemeriksaan Hari Ini</span>
                    <span class="bg-white text-blue-600 px-4 py-1 rounded-full text-xs shadow-inner">
                        <i class="far fa-calendar-alt mr-1"></i> {{ now()->format('d M Y') }}
                    </span>
                </div>

                <div class="p-8">
                    <table class="w-full text-left">
                        <thead class="text-blue-600 border-b-2 border-blue-100 font-black uppercase text-sm tracking-wider">
                            <tr>
                                <th class="py-4">No. Reg</th>
                                <th class="py-4">Nama Pasien</th>
                                <th class="py-4">Keluhan</th>
                                <th class="py-4">Tensi</th>
                                <th class="py-4 text-center">Aksi / Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($antrian as $a)
                                <tr class="hover:bg-blue-50/50 transition-colors font-bold text-slate-700 group">
                                    <td class="py-4 font-mono text-blue-600">{{ $a->no_registrasi }}</td>
                                    <td class="py-4">{{ $a->member->nama_lengkap }}</td>
                                    <td class="py-4 text-slate-500 font-medium italic">"{{ $a->keluhan }}"</td>
                                    <td class="py-4 text-slate-500">{{ $a->tensi ?? '-' }}</td>
                                    <td class="py-4 text-center">
                                        @if ($a->status == 'antri')
                                            <a href="{{ route('klinik.periksa', $a->id) }}"
                                                class="inline-block px-6 py-2 bg-blue-600 text-white rounded-xl text-xs font-black uppercase hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:-translate-y-1">
                                                <i class="fas fa-stethoscope mr-1"></i> Berikan Tindakan
                                            </a>
                                        @elseif ($a->transaksiFaskes && $a->transaksiFaskes->status == 'closed' && $a->status == 'selesai')
                                            <div class="flex flex-col items-center gap-2">
                                                <span
                                                    class="inline-block px-6 py-1 rounded-xl text-xs font-black bg-green-100 text-green-600 uppercase border border-green-200 shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                                </span>
                                                <a href="{{ route('klinik.show', $a->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 text-[10px] font-black uppercase tracking-tighter underline decoration-2 underline-offset-4 transition-all">
                                                    <i class="fas fa-eye mr-1"></i> Lihat Rekam Medis
                                                </a>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-center gap-2">
                                                {{-- PERBAIKAN: Tombol Bayar menggunakan JavaScript SweetAlert --}}
                                                <button onclick="prosesBayarKlinik('{{ $a->transaksiFaskes->kode_transaksi }}')"
                                                    class="inline-block px-6 py-2 bg-orange-600 text-white rounded-xl text-xs font-black uppercase hover:bg-green-700 shadow-lg shadow-green-200 transition-all hover:-translate-y-1">
                                                    <i class="fas fa-money-bill-wave mr-1"></i> Bayar Rekam Medis
                                                </button>
                                                
                                                <a href="{{ route('klinik.show', $a->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 text-[10px] font-black uppercase tracking-tighter underline decoration-2 underline-offset-4 transition-all">
                                                    <i class="fas fa-eye mr-1"></i> Lihat Rekam Medis
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                        <i class="fas fa-hospital-user text-5xl mb-4 block opacity-20"></i>
                                        Belum ada antrian pasien hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-8 border-t border-gray-100 pt-6">
                        {{ $antrian->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Logika Cetak Struk Otomatis --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /**
         * 1. TRIGGER CETAK STRUK PENDAFTARAN (Setelah Pasien Baru Didaftarkan)
         */
        @if(session('cetak_struk'))
            Swal.fire({
                title: 'Pendaftaran Berhasil!',
                text: "Ingin mencetak struk biaya pendaftaran?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk',
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("/cetak-struk/{{ session('cetak_struk') }}", '_blank');
                }
            });
        @endif

        /**
         * 2. LOGIKA PROSES BAYAR REKAM MEDIS & CETAK STRUK
         */
        function prosesBayarKlinik(kode) {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Selesaikan pembayaran untuk transaksi " + kode + "?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Bayar Sekarang!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request bayar via AJAX agar tidak refresh mendadak
                    fetch("/klinik/bayar/" + kode, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(res => {
                        if(res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                text: 'Lunas. Ingin mencetak struk rekam medis?',
                                showCancelButton: true,
                                confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk',
                                cancelButtonText: 'Selesai',
                                confirmButtonColor: '#198754',
                            }).then((printRes) => {
                                if (printRes.isConfirmed) {
                                    window.open("/cetak-struk/" + res.kode_transaksi, '_blank');
                                    location.reload();
                                } else {
                                    location.reload();
                                }
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error Sistem', 'Gagal memproses pembayaran. Silakan coba lagi.', 'error');
                    });
                }
            });
        }
    </script>
@endsection