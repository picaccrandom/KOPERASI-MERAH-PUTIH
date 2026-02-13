@extends('layouts.master')

@section('title', 'Pinjaman - Koperasi Merah Putih')

@section('content')
    {{-- @php
        dd($allPeminjamans)
    @endphp --}}
    <div class="mx-12  px-4 bg-orange-900/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2 flex justify-between items-center">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class="border-l-8 border-l-orange-400 pl-4 flex flex-col justify-center items-start gap-3">
                    <span class=" text-white text-5xl  uppercase font-semibold">
                        Layanan <span class="bg-orange-400 text-white px-2 py-1 rounded-xl shadow-md">PEMINJAMAN</span></span>
                    <p class="italic text-white">Dashboard Layanan Peminjaman Koperasi Merah Putih</p>
                </div>
            </div>
            <div class="flex justify-center items-center ">
                <a type="a" href="{{ route('pinjaman.create') }}"
                    class="w-full h-full px-10 py-6 tracking-wider bg-red-600 text-2xl hover:bg-red-700 text-white rounded-2xl hover: flex justify-center items-center  text-decoration-none shadow-md  uppercase font-black">
                    <i class="fa-solid fa-money-bill-transfer mr-8"></i>
                    AJUKAN PEMINJAMAN
                </a>
            </div>

        </div>
        <hr class="m-0 p-0 mb-4">
        <main class="flex gap-3 ">
            <div class="w-full flex flex-col  gap-4">
                <section class="h-[80dvh] flex gap-2 mb-2" id="form-pencarian">
                    <div class="w-[30%] flex flex-col justify-center items-start gap-y-6">
                        <div class="h-1/2 bg-white w-full  rounded-2xl overflow-hidden shadow-md">
                            {{-- Form Pencarian dan Filter akan ditempatkan di sini --}}
                            <label for="search-pinjaman"
                                class="uppercase bg-black text-white font-semibold w-full px-4 py-2.5 flex justify-center items-center">
                                <i class="fas fa-search mr-2"></i>
                                Search Member
                            </label>
                            <div class="p-4 pt-10 flex flex-col w-full gap-y-6">
                                <label for="" class="text-slate-600">Masukan Kode Pinjaman</label>
                                <input type="text" id="search-pinjaman"
                                    class="w-full ml-4 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64"
                                    placeholder="SP-P-312XXXX" autocomplete="false">
                                <div class="dropdown-menu w-[20 %] text-base hidden" id="dropdown-member"></div>
                                <a href="{{ route('pinjaman.index') }}"
                                    class="bg-blue-400 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-decoration-none text-center font-semibold">
                                    <i class="fa-solid fa-arrows-rotate mr-2"></i>
                                    Refresh
                                </a>
                            </div>
                        </div>
                        <div class="h-1/2 bg-blue-50 border border-blue-200 rounded-2xl p-4 hover:bg-blue-100 shadow-md">
                            {{-- Keterangan --}}
                            <div class="">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Keterangan : </strong>
                            </div>
                            <p class="text-lg text-blue-800 flex flex-col  items-center pt-2">
                                Penagihan pinjaman dapat dilakukan dengan mengakses detail pinjaman pada
                                masing-masing anggota. Pastiakan untuk memeriksa data dengan teliti.
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white w-full overflow-y-auto rounded-2xl border border-gray-200 overflow-hidden shadow-md">
                        <div
                            class="bg-orange-400 px-4 py-3 text-white font-semibold uppercase flex justify-start items-center">
                            <i class="fa-solid fa-square-poll-horizontal text-2xl mr-3"></i>
                            Panel Pencarian Pinjaman / Angsuran
                        </div>
                        <div class="overflow-x-auto flex p-10 px-10">
                            <table class="text-center min-w-full space-y-4">
                                <thead class="bg-orange-300">
                                    <tr
                                        class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                                        <th>KODE PINJAMAN</th>
                                        <th>NAMA ANGGOTA</th>
                                        <th>TOTAL PINJAMAN</th>
                                        <th>LAMA BAYAR</th>
                                        <th>JATUH TEMPO</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="hidden">
                                    @forelse ($peminjamans as $peminjaman)
                                        <tr class="pinjaman-row [&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50"
                                            data-index="{{ $loop->index }}">
                                            <td class="font-medium">{{ $peminjaman->no_transaksi_sp }}</td>
                                            <td>
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="font-medium text-gray-900">
                                                            {{ $peminjaman->member->nama_lengkap }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="font-bold text-red-600 text-left text-sm">Rp.
                                                {{ number_format($peminjaman->Nominal, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($peminjaman->COA == 'Pinjam')
                                                    {{ $peminjaman->angsuranPeminjamans->count() }} Bulan
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d F Y') }}
                                            </td>
                                            <td>
                                                <div class="flex space-x-2">
                                                    @if ($peminjaman->COA == 'Pinjam')
                                                        <a href="{{ route('pinjaman.detail', $peminjaman->no_transaksi_sp) }}"
                                                        @else <a
                                                            href="{{ route('pinjaman.detailBon', $peminjaman->no_transaksi_sp) }}"
                                                            @endif
                                                            class="text-blue-600 hover:text-blue-900" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('pinjaman.destroy', $peminjaman->id) }}"
                                                            onclick="confirmDelete(event, this)" method="POST"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @if ($peminjaman->COA == 'Pinjam')
                                            <tr class="bg-slate-100 ket-pinjaman" data-index="{{ $loop->index }}">
                                                <td colspan="8" class="text-start  pl-12 py-4">
                                                    <i class="bi bi-arrow-return-right text-black"></i>
                                                    Angsuran Terdekat:
                                                    <a href="{{ route('pinjaman.detail', $peminjaman->no_transaksi_sp) }}"
                                                        class="text-blue-600 hover:underline">
                                                        {{ $peminjaman->angsuranBelum->batas_bayar ?? 'Belum ada angsuran' }}
                                                        -
                                                        Rp.
                                                        {{ number_format($peminjaman->angsuranBelum->jumlah_angsuran ?? 0, 0, ',', '.') }}
                                                        | Angsuran Ke -
                                                        {{ $peminjaman->angsuranBelum->angsuran_ke ?? 'Belum ada angsuran' }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-4xl text-slate-300 italic h-[28rem]">
                                                <i class="fa-solid fa-credit-card mr-2"></i>
                                                Tidak ada data Pinjaman
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
                <!-- Data Table -->
                <hr>
                <!--History Table -->
                <div class="bg-white  w-full overflow-y-auto rounded-2xl border border-gray-200 overflow-hidden shadow-md">

                    <div class="flex justify-between items-center bg-slate-400 px-4 py-3">
                        <div class="  text-white font-semibold uppercase h-full flex justify-center items-center tracking-widest text-2xl ">
                            <i class="fa-solid fa-table mr-3"></i>
                            Histori Peminjaman Anggota
                        </div>
                        <div class="relative">
                            <input type="text" placeholder="No Pinjaman / Nama ..." id="search-pinjaman-history"
                                class="pl-10 pr-4 py-2 border bg-white text-slate-500 border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-[40rem]">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="h-[70dvh] overflow-x-auto overflow-y-auto">
                        <div class="flex pb-10 pt-2 px-10">
                            <table class="text-center min-w-full space-y-4">
                                <thead class="bg-black">
                                    <tr
                                        class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                                        <th>NO.</th>
                                        <th>NAMA ANGGOTA</th>
                                        <th>TANGGAL PINJAM</th>
                                        <th>TOTAL PINJAMAN</th>
                                        <th>LAMA BAYAR</th>
                                        <th>JATUH TEMPO</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="pinjaman-history">
                                    @foreach ($peminjamans as $peminjaman)
                                        <tr class="{{ $peminjaman->member->status == 'banned'? 'bg-red-600/40' : '' }} [&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50"
                                            data-index="{{ $loop->index }}">
                                            <td class="font-medium cursor-pointer hover:underline hover:text-blue-600" onclick="window.location='{{route('pinjaman.detail', $peminjaman->no_transaksi_sp)}}'">{{ $peminjaman->no_transaksi_sp }}</td>
                                            <td>
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="font-medium text-gray-900 cursor-pointer hover:underline hover:text-blue-600" onclick="window.location='{{route('member.show', ['modul' => 'pinjaman', 'id' => $peminjaman->member->id])}}'">
                                                           {{ $peminjaman->member->nama_lengkap }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">{{ $peminjaman->no_hp }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d F Y') }}</td>
                                            <td class="font-bold text-red-600 text-left text-sm">Rp.
                                                {{ number_format($peminjaman->Nominal, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if ($peminjaman->COA == 'Pinjam')
                                                    {{ $peminjaman->angsuranPeminjamans->count() }} Bulan
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_jatuh_tempo)->format('d F Y') }}
                                            </td>
                                            <td>
                                                <div class="flex justify-center items-center gap-3">
                                                    <form
                                                        action="{{ route('pinjaman.destroy', $peminjaman->no_transaksi_sp) }}"
                                                        method="POST" class="d-inline delete-form"
                                                        data-name="{{ $peminjaman->member->nama_lengkap }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 bg-transparent border-0"
                                                            title="Hapus">
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
            </div>
        </main>
    </div>

@endsection


@section('scripts')

    <script>
        const pinjamanRows = document.querySelectorAll('.pinjaman-row');
        const ketRows = document.querySelectorAll('.ket-pinjaman');

        document.getElementById('search-pinjaman').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const tbody = document.querySelector('table tbody');

            if (filter === '') {
                tbody.classList.add('hidden');
                return;
            } else {
                tbody.classList.remove('hidden');
            }

            pinjamanRows.forEach(row => {
                const index = row.dataset.index;
                const ketRow = document.querySelector(`.ket-pinjaman[data-index="${index}"]`);

                const kode = row.cells[0].textContent.toLowerCase();
                const nama = row.cells[1].textContent.toLowerCase();
                const total = row.cells[2].textContent.toLowerCase();
                const lamabayar = row.cells[3].textContent.toLowerCase();
                const jatuhTempo = row.cells[4].textContent.toLowerCase();

                const match =
                    kode.includes(filter) ||
                    nama.includes(filter) ||
                    total.includes(filter) ||
                    lamabayar.includes(filter) ||
                    jatuhTempo.includes(filter);

                if (match) {
                    row.style.display = '';
                    if (ketRow) ketRow.style.display = '';
                } else {
                    row.style.display = 'none';
                    if (ketRow) ketRow.style.display = 'none';
                }
            });
        });

        // SweetAlert untuk konfirmasi hapus
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const memberName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Data simpanan untuk <strong>${memberName}</strong> akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        document.getElementById('search-pinjaman-history').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const tbody = document.getElementById('pinjaman-history');

            console.log(filter);

            if (filter === '') {
                Array.from(tbody.rows).forEach(row => {
                    row.style.display = '';
                });
                return;
            }

            Array.from(tbody.rows).forEach(row => {
                const kode = row.cells[0].textContent.toLowerCase();
                const nama = row.cells[1].textContent.toLowerCase();
                const tanggal = row.cells[2].textContent.toLowerCase();
                const lamaBayar = row.cells[4].textContent.toLowerCase();
                const jatuhTempo = row.cells[6].textContent.toLowerCase();

                const match =
                    kode.includes(filter) ||
                    nama.includes(filter) ||
                    tanggal.includes(filter) ||
                    lamaBayar.includes(filter) ||
                    jatuhTempo.includes(filter);

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
