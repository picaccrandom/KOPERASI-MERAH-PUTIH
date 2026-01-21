@extends('layouts.master')

@section('title', 'Pinjaman - Koperasi Merah Putih')

@section('content')
    {{-- @php
        dd($peminjamans)
    @endphp --}}
    <div class="mx-10 px-4 bgwhite/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class="border-l-8 border-l-red-600 pl-4">
                    <div class=" text-white text-4xl text-shadow-lg uppercase font-extrabold tracking-wider">Data
                        Pinjaman</div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search nama, status..." id="search-pinjaman"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    <a type="a" href="{{ route('pinjaman.create') }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center  text-decoration-none shadow-md text-lg uppercase font-semibold">
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

            <div class="overflow-x-auto flex p-10 px-10">
                <table class="text-center min-w-full overflow-hidden space-y-4">
                    <thead class="bg-orange-300">
                        <tr
                            class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                            <th>NO.</th>
                            <th>NAMA ANGGOTA</th>
                            <th>TANGGAL PINJAM</th>
                            <th>TOTAL PINJAMAN</th>
                            <th>JENIS PINJAMAN</th>
                            <th>LAMA BAYAR</th>
                            <th>JATUH TEMPO</th>
                            <th>STATUS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peminjamans as $peminjaman)
                            <tr class="[&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50">
                                <td class="font-medium">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $peminjaman->nama_lengkap }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $peminjaman->no_hp }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</td>
                                <td class="font-bold text-red-600 text-left text-sm">Rp.
                                    {{ number_format($peminjaman->total_pinjaman, 0, ',', '.') }}</td>
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
                                        <a href="{{ route('pinjaman.detail', $peminjaman->no_transaksi_sp) }}"
                                            class="text-blue-600 hover:text-blue-900" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                            <form action="{{ route('pinjaman.destroy', $peminjaman->id) }}" 
                                                onclick="confirmDelete(event, this)" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection


@section('scripts')

    <script>
        document.getElementById('search-pinjaman').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('table tbody tr    ');

            rows.forEach(function(row) {
                let namaAnggota = row.cells[1].textContent.toLowerCase();
                let jatuhtempo = row.cells[2].textContent.toLowerCase();
                let status = row.cells[7].textContent.toLowerCase();
                let lamaBayar = row.cells[5].textContent.toLowerCase();
                if (namaAnggota.indexOf(filter) > -1 || jatuhtempo.indexOf(filter) > -1 || status.indexOf(
                        filter) > -1 || lamaBayar.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

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

        function confirmDelete(event, form) {
            event.preventDefault(); // Prevent form submission

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pinjaman akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    form.submit();
                }
            });
            // }).then(response => {
            //     if (response.ok) {
            //         Swal.fire(
            //             'Dihapus!',
            //             'Data pinjaman telah dihapus.',
            //             'success'
            //         )
            //     } else {
            //         Swal.fire(
            //             'Gagal!',
            //             'Terjadi kesalahan saat menghapus data pinjaman.',
            //             'error'
            //         );
            //     }
            // });
        }
    </script>
@endsection
