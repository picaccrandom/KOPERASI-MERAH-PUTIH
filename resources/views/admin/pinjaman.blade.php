@extends('layouts.master')

@section('title', 'Pinjaman - Koperasi Merah Putih')

@section('content')

    <div class="mx-10 px-4 bgwhite/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class="border-l-8 border-l-red-600 pl-4">
                    <div class="text-2xl text-white text-4xl text-shadow-lg uppercase font-extrabold tracking-wider">Data
                        Pinjaman</div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    <a type="a" href="{{ route('pinjaman.create') }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center text-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pinjaman
                    </a>
                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-4">
        <p class="text-slate-600 pl-4 ">Kelola Data Peminjaman dengan Teliti</p>
        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-md">

            <div class="overflow-x-auto flex p-10">
                <table class="text-center min-w-full  overflow-hidden">
                    <thead class="bg-orange-300">
                        <tr>
                            <th class="text-left">NO.</th>
                            <th class="text-left">NAMA ANGGOTA</th>
                            <th class="text-left">TANGGAL PINJAM</th>
                            <th class="text-left">TOTAL PINJAMAN</th>
                            <th class="text-left">JENIS PINJAMAN</th>
                            <th class="text-left">LAMA BAYAR</th>
                            <th class="text-left">JATUH TEMPO</th>
                            <th class="text-left">STATUS</th>
                            <th class="text-left">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjamans as $peminjaman)
                            <tr>
                                <td class="font-medium">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $peminjaman->member->nama_lengkap }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $peminjaman->member->no_hp }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</td>
                                <td class="font-bold text-red-600 text-left">Rp. {{ $peminjaman->total_pinjaman }}</td>
                                <td>
                                    @if ($peminjaman->jenis == 'uang')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800 uppercase">{{ ucfirst($peminjaman->jenis) }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800 uppercase">{{ ucfirst($peminjaman->jenis) }}</span>
                                    @endif
                                </td>
                                <td>{{ $peminjaman->tenor }} Bulan</td>
                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d F Y') }}</td>
                                <td>
                                    @if ($peminjaman->status == 'aktif')
                                        <span
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $peminjaman->status }}</span>
                                    @elseif ($peminjaman->status == 'lunas')
                                        <span
                                            class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $peminjaman->status }}</span>
                                    @else
                                        <span
                                            class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $peminjaman->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pinjaman.detail', $peminjaman->id) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="" class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>



    <!-- Template for Detail Cards -->
    <template id="detailCardTemplate">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">#TITLE#</p>
                    <p class="text-2xl font-bold #COLOR#">#VALUE#</p>
                </div>
                <i class="fas #ICON# text-2xl text-gray-300"></i>
            </div>
        </div>

    </template>
@endsection


@section('scripts')

    <script>
        @if (session('success'))
            {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirma: false
                });
            }
        @elseif (session('error')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirma: true
                });
            }
        @endif
    </script>
@endsection
