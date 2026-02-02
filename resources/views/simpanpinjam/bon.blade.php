@extends('layouts.master')

@section('title', 'Pinjaman - Koperasi Merah Putih')

@section('content')
    {{-- @php
        dd($Bons)
    @endphp --}}
    <div class="mx-10  px-4 bg-teal-900/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class=" px-6 py-2  flex justify-between items-center">
            <div class="border-l-8 border-l-sky-400 pl-4 flex flex-col justify-center items-start gap-3">
                <span class=" text-white text-5xl  uppercase font-semibold">
                    Layanan <span class="bg-sky-400 text-white px-2 py-1 rounded-xl shadow-md">BON</span></span>
                <p class="italic text-white">Dashboard Layanan BON Koperasi Merah Putih</p>
            </div>
        </div>
        <hr class="m-0 p-0 mb-4">
        <div class="relative w-2/3 mb-4">
            <input type="text" placeholder="Search nama, status..." id="search-pinjaman"
                class="bg-white pl-10 pr-4 py-2 w-full border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
        <!-- Data Table -->
        <div class="bg-white h-[90%] overflow-y-auto rounded-lg border border-gray-200 overflow-hidden shadow-md">
            <div class="overflow-x-auto flex p-10 px-10">
                <table class="text-center min-w-full space-y-4">
                    <thead class="bg-sky-500">
                        <tr
                            class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                            <th>NO.</th>
                            <th>NAMA ANGGOTA</th>
                            <th>TANGGAL PINJAM</th>
                            <th>TOTAL PINJAMAN</th>
                            <th>JENIS</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($Bons as $bon)
                            <tr class="pinjaman-row [&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50"
                                data-index="{{ $loop->index }}">
                                <td class="font-medium">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex justify-center items-center">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $bon->member->nama_lengkap }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($bon->tanggal)->format('d F Y') }}</td>
                                <td class="font-bold text-red-600 text-center text-sm">Rp.
                                    {{ number_format($bon->Nominal, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span
                                        class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">BON</span>
                                </td>
                                <td>
                                    <div class="flex justify-center items-center space-x-2">
                                        <a href="{{ route('bon.detailBon', $bon->no_transaksi_sp) }}"
                                            class="text-white px-1 py-0.5 rounded-md shadow-md hover:bg-blue-600 bg-blue-500 !no-underline"
                                            title="Detail">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span class="uppercase text-sm font-semibold">Tinjau</span>
                                        </a>

                                        <form action="{{ route('bon.destroy', $bon->id) }}"
                                            onclick="confirmDelete(event, this)" method="POST"
                                            class="inline border-l-2 border-l-slate-400 pl-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600  hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-4xl text-slate-300 italic h-[28rem]">
                                    <i class="fa-solid fa-receipt mr-2 "></i>
                                    Tidak ada data BON
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection


@section('scripts')

    <script>
        const pinjamanRows = document.querySelectorAll('.pinjaman-row');
        const ketRows = document.querySelectorAll('.ket-pinjaman');

        document.getElementById('search-pinjaman').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const tbody = document.querySelector('table tbody');


            pinjamanRows.forEach(row => {
                const index = row.dataset.index;

                const nama = row.cells[1].textContent.toLowerCase();
                const tanggal = row.cells[2].textContent.toLowerCase();
                const total = row.cells[3].textContent.toLowerCase();


                const match =
                    nama.includes(filter) ||
                    tanggal.includes(filter) ||
                    total.includes(filter)

                if (match) {
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
