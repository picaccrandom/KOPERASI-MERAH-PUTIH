@extends('layouts.master')

@section('title', 'Simpanan - Koperasi Merah Putih')

@section('content')
    <div class="mx-10 bg-teal-900/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class=" px-6 py-2  flex justify-between items-center">
                    <div class="border-l-8 border-l-teal-400 pl-4 flex flex-col justify-center items-start gap-3">
                        <span class=" text-white text-5xl  uppercase font-semibold">
                            Layanan <span
                                class="bg-teal-400 text-white px-2 py-1 rounded-xl shadow-md">SIMPANAN</span></span>
                        <p class="italic text-white">Dashboard Layanan Simpanan Koperasi Merah Putih</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">

                    <a type="a" href="{{ route('simpanan.create') }}"
                        class="px-4 py-2 text-2xl bg-red-600 hover:bg-red-700 text-white rounded flex items-center  text-decoration-none shadow-md uppercase font-semibold">
                        <i class="fa-solid fa-circle-dollar-to-slot mr-4"></i>
                        Tambah Simpanan
                    </a>
                    <a type="a" href="{{ route('simpanan.tarik') }}"
                        class="px-4 py-2 text-2xl bg-orange-600 hover:bg-orange-700 text-white rounded flex items-center  text-decoration-none shadow-md  uppercase font-semibold">
                        <i class="fa-solid fa-wallet mr-2"></i>
                        Tarik Simpanan
                    </a>

                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-4">
        <section class="h-[80dvh] px-4 flex gap-2 mb-2" id="form-pencarian">
            <div class="w-[20%] flex flex-col justify-center items-start gap-y-4">
                <div class="h-1/2 w-full bg-white  rounded-3xl overflow-hidden shadow-md">
                    {{-- Form Pencarian dan Filter akan ditempatkan di sini --}}
                    <label for="search-data-simpanan"
                        class="uppercase bg-black text-white text-center font-semibold w-full px-4 py-2.5">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        Search Member
                    </label>
                    <div class="p-4 pt-10 flex flex-col w-full ">
                        <label for="" class="text-slate-400">Masukan Identitas Member</label>
                        <input type="text" id="search-data-simpanan"
                            class="w-full ml-4 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64"
                            placeholder="Masukkan nama atau NIK member...">
                        <div class="dropdown-menu w-[24%] text-base hidden" id="dropdown-member"></div>
                        <div class="rounded-md overflow-hidden mb-2 flex justify-between items-center">
                            <button type="submit" id="cari-member"
                                class="mt-4 w-full h-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md font-semibold">
                                <span><i class="fa-solid fa-users-viewfinder mr-2"></i>Cari Member</span></button>
                        </div>
                        <a href="{{ route('simpanan.index') }}"
                            class="bg-blue-400 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-decoration-none text-center font-semibold">
                            <span><i class="fa-solid fa-arrows-rotate mr-1"></i>Refresh</span></a>
                    </div>
                </div>
                <div class="h-1/2 bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 shadow-md">
                    {{-- Keterangan --}}
                    <div class="">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Keterangan : </strong>
                    </div>
                    <p class="text-lg text-blue-800 flex flex-col items-center pt-2">
                        Simpanan wajib dibayarkan setiap akhir bulan/gaji karyawan
                    </p>
                </div>
            </div>
            <div class="w-[80%] bg-white rounded-2xl shadow-md overflow-hidden">
                {{-- tampil Data Simpanan akan ditempatkan di sini --}}
                <div class="bg-teal-400 px-4 py-3 text-white font-semibold uppercase flex justify-start items-center">
                    <i class="fa-solid fa-square-poll-horizontal text-2xl mr-3"></i>
                    Panel Pencarian Simpanan
                </div>
                <div class="px-10 pt-3">
                    <!-- Info Anggota -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class=" space-y-8">
                            <div>
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="text-lg font-bold text-gray-800" id="nama-anggota">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">NIK</p>
                                <p class="text-lg text-gray-800" id="nik">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">NO. TELP/WA</p>
                                <p class="text-lg text-gray-800" id="no-telp">-</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Total Saldo Keseluruhan</p>
                                <p class="text-lg font-bold text-sky-600">
                                    Rp. <span id="total_simpanan">-</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Saldo Dapat Diambil</p>
                                <p class="text-lg font-bold text-red-600">
                                    Rp. <span id="total_sukarela">-</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <div class="bg-green-600 px-2 shadow-md inline-block rounded-md">
                                    <p class="text-white m-0" id="status">none</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-500 mb-1">ALAMAT</p>
                        <p class="text-gray-800" id="alamat">-</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Data Table -->
        <hr>
        <div class="bg-white h-[80dvh] rounded-2xl border border-gray-200 overflow-hidden shadow-md">
            <div class=" bg-slate-400 mb-2 text-white font-semibold px-8 py-3 uppercase flex justify-between items-center">
                <div class=" text-2xl">
                    <i class="fa-regular fa-file mr-4"></i>Histori
                    Simpanan dan
                    Penarikan
                </div>
                <div class="flex items-center gap-4">
                    <div>
                        <select id="filter-kategori"
                            class="px-3 py-2.5 bg-white text-slate-400 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-xs">
                            <option value="">Semua Kategori</option>
                            <option value="pokok" class="bg-green-800/40 text-white">Pokok</option>
                            <option value="wajib" class="bg-blue-800/40 text-white">Wajib</option>
                            <option value="sukarela" class="bg-orange-800/40 text-white">Sukarela</option>
                        </select>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Search..." id="search-simpanan"
                            class="pl-10 pr-4 py-2 text-slate-400 bg-white border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-[25rem]">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-autoflex pb-10 px-10 ">
                <div class="w-full h-140 overflow-y-auto">
                    <table class="text-center min-w-full overflow-hidden space-y-4 ">
                        <thead class="bg-black">
                            <tr
                                class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                                <th>NO.</th>
                                <th>NAMA ANGGOTA</th>
                                <th>KATEGORI</th>
                                <th>TANGGAL</th>
                                <th>NOMINAL</th>
                                <th>KETERANGAN</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="min-h-full">
                            @forelse ($simpanans as $simpanan)
                                <tr class="[&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50 ">
                                    <td class="font-medium">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-full">
                                                <div class=" text-gray-900 font-semibold">
                                                    {{ $simpanan->member->nama_lengkap }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($simpanan->simpananDetails->first()->jenis == 'wajib')
                                            <span
                                                class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-blue-100 text-blue-800">
                                            @elseif ($simpanan->simpananDetails->first()->jenis == 'pokok')
                                                <span
                                                    class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-green-100 text-green-800">
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-orange-100 text-orange-800">
                                        @endif
                                        {{ $simpanan->simpananDetails->first()->jenis }}
                                        </span>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($simpanan->simpananDetails->first()->tanggal)->format('d F Y') }}
                                    </td>
                                    <td class="font-bold text-red-600">Rp
                                        {{ number_format($simpanan->simpananDetails->sum('saldo'), 0, ',', '.') }}</td>
                                    <td>
                                        @if ($simpanan->COA == 'Simpan')
                                            <span
                                                class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-green-400 text-white">
                                                Masuk
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-red-400 text-white">
                                                Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex justify-center items-center gap-3">
                                            <form action="{{ route('simpanan.destroy', $simpanan->id) }}" method="POST"
                                                class="d-inline delete-form"
                                                data-name="{{ $simpanan->member->nama_lengkap }}">
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
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-4xl text-gray-500 italic">
                                        <div class="p-48">
                                            <i class="fa-solid fa-money-bills mr-2"></i>
                                            Tidak ada data Simpanan
                                        </div>
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


                // Search fungsi
                const searchInput = document.getElementById('search-simpanan');
                const kategoriFilter = document.getElementById('filter-kategori');
                const members = @json($members);
                let memberId = 0;



                document.getElementById('cari-member').addEventListener('click', function() {
                    if (memberId) {
                        fetch(`/simpanan/show/${memberId}`, {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Gagal mengambil data dari server.');
                                return res.json();
                            })
                            .then(data => {
                                // Update informasi anggota di halaman
                                document.getElementById('nama-anggota').textContent = data.member.nama_lengkap;
                                document.getElementById('nik').textContent = data.member.nik;
                                document.getElementById('no-telp').textContent = data.member.nomor_hp;
                                document.getElementById('alamat').textContent = data.member.alamat;
                                document.getElementById('total_simpanan').textContent = new Intl.NumberFormat('id-ID')
                                    .format(data.total_simpanan_all);
                                document.getElementById('total_sukarela').textContent = new Intl.NumberFormat('id-ID')
                                    .format(data.total_simpanan_sukarela);
                                // Update status
                                console.log(data);
                                const statusElem = document.getElementById('status');
                                if (data.status === 'aktif') {
                                    statusElem.textContent = 'Aktif';
                                    statusElem.parentElement.className =
                                        'bg-green-600 px-2 shadow-md inline-block rounded-md';
                                } else {
                                    statusElem.textContent = 'Nonaktif';
                                    statusElem.parentElement.className =
                                        'bg-orange-600 px-2 shadow-md inline-block rounded-md';
                                }
                            })
                            .catch(err => {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Gagal mengambil data anggota. Cek koneksi Anda.',
                                    timer: 3000,
                                    showConfirma: true
                                });
                                console.error(err);
                            });
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Silakan pilih member terlebih dahulu dari hasil pencarian!',
                            timer: 3000,
                            showConfirma: true
                        });
                    }

                });

                document.getElementById('search-data-simpanan').addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const filtered = members.filter(m =>
                        m.nama_lengkap.toLowerCase().includes(query) ||
                        m.nik.toLowerCase().includes(query)
                    );

                    console.log(filtered);
                    const dropdown = document.getElementById('dropdown-member');

                    dropdown.innerHTML = '';

                    if (filtered.length > 0 && query !== '') {
                        filtered.forEach(m => {
                            const option = document.createElement('a');
                            option.classList.add('dropdown-item', 'cursor-pointer');
                            option.innerHTML = `<div class="d-flex   justify-content-between align-items-center">
                                            <div style="line-height: 1.2;">
                                                <small class="text-danger fw-bold d-block">${m.nik}</small>
                                                <strong class="text-dark text-uppercase font-black" style="font-size: 1rem;">${m.nama_lengkap}</strong>
                                            </div>
                                            <i class="fas fa-user-plus text-muted fa-lg"></i>
                                        </div>`;

                            option.addEventListener('click', () => {
                                document.getElementById('search-data-simpanan').value =
                                    `${m.nik} - ${m.nama_lengkap}`;
                                memberId = m.id; // Set the member ID
                                dropdown.classList.add('hidden');
                                dropdown.classList.remove('show');
                            });
                            dropdown.appendChild(option);
                        });
                        dropdown.classList.remove('hidden');
                        dropdown.classList.add('show');
                    } else {
                        dropdown.classList.add('hidden');
                        dropdown.classList.remove('show');
                    }
                });


                function filterTable() {
                    const searchValue = searchInput.value.toLowerCase();
                    const kategoriValue = kategoriFilter.value.toLowerCase();
                    const rows = document.querySelectorAll('table tbody tr');

                    rows.forEach(row => {
                        const namaAnggota = row.cells[1].textContent.toLowerCase();
                        const kategori = row.cells[2].textContent.toLowerCase();
                        const tanggal = row.cells[3].textContent.toLowerCase();
                        const keterangan = row.cells[5].textContent.toLowerCase();

                        const cocokSearch =
                            namaAnggota.includes(searchValue) ||
                            kategori.includes(searchValue) ||
                            tanggal.includes(searchValue) ||
                            keterangan.includes(searchValue);

                        const cocokKategori =
                            kategoriValue === '' || kategori.includes(kategoriValue);

                        if (cocokSearch && cocokKategori) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }


                searchInput.addEventListener('input', filterTable);
                kategoriFilter.addEventListener('change', filterTable);

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
