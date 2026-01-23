@extends('layouts.master')

@section('title', 'Simpanan - Koperasi Merah Putih')

@section('content')
    <div class="mx-10 px-4 bgwhite/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class="border-l-8 border-l-green-500 pl-4">
                    <div class=" text-white text-6xl text-shadow-lg uppercase font-extrabold tracking-wider">simpanan</div>
                </div>

                <div class="flex items-center space-x-3">

                    <a type="a" href="{{ route('simpanan.create') }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center  text-decoration-none shadow-md text-sm uppercase font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-piggy-bank mr-2" viewBox="0 0 16 16">
                            <path
                                d="M5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0m1.138-1.496A6.6 6.6 0 0 1 7.964 4.5c.666 0 1.303.097 1.893.273a.5.5 0 0 0 .286-.958A7.6 7.6 0 0 0 7.964 3.5c-.734 0-1.441.103-2.102.292a.5.5 0 1 0 .276.962" />
                            <path fill-rule="evenodd"
                                d="M7.964 1.527c-2.977 0-5.571 1.704-6.32 4.125h-.55A1 1 0 0 0 .11 6.824l.254 1.46a1.5 1.5 0 0 0 1.478 1.243h.263c.3.513.688.978 1.145 1.382l-.729 2.477a.5.5 0 0 0 .48.641h2a.5.5 0 0 0 .471-.332l.482-1.351c.635.173 1.31.267 2.011.267.707 0 1.388-.095 2.028-.272l.543 1.372a.5.5 0 0 0 .465.316h2a.5.5 0 0 0 .478-.645l-.761-2.506C13.81 9.895 14.5 8.559 14.5 7.069q0-.218-.02-.431c.261-.11.508-.266.705-.444.315.306.815.306.815-.417 0 .223-.5.223-.461-.026a1 1 0 0 0 .09-.255.7.7 0 0 0-.202-.645.58.58 0 0 0-.707-.098.74.74 0 0 0-.375.562c-.024.243.082.48.32.654a2 2 0 0 1-.259.153c-.534-2.664-3.284-4.595-6.442-4.595M2.516 6.26c.455-2.066 2.667-3.733 5.448-3.733 3.146 0 5.536 2.114 5.536 4.542 0 1.254-.624 2.41-1.67 3.248a.5.5 0 0 0-.165.535l.66 2.175h-.985l-.59-1.487a.5.5 0 0 0-.629-.288c-.661.23-1.39.359-2.157.359a6.6 6.6 0 0 1-2.157-.359.5.5 0 0 0-.635.304l-.525 1.471h-.979l.633-2.15a.5.5 0 0 0-.17-.534 4.65 4.65 0 0 1-1.284-1.541.5.5 0 0 0-.446-.275h-.56a.5.5 0 0 1-.492-.414l-.254-1.46h.933a.5.5 0 0 0 .488-.393m12.621-.857a.6.6 0 0 1-.098.21l-.044-.025c-.146-.09-.157-.175-.152-.223a.24.24 0 0 1 .117-.173c.049-.027.08-.021.113.012a.2.2 0 0 1 .064.199" />
                        </svg>
                        Tambah Simpanan
                    </a>
                    <a type="a" href="{{ route('simpanan.tarik') }}"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded flex items-center  text-decoration-none shadow-md text-sm uppercase font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="bi bi-wallet2 mr-2" viewBox="0 0 16 16">
                            <path
                                d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5z" />
                        </svg> Tarik Simpanan
                    </a>

                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-4">
        <section class="h-[80dvh] flex gap-2 mb-2" id="form-pencarian">
            <div class="w-[20%] flex flex-col justify-center items-start gap-y-4">
                <div class="h-1/2 bg-white  rounded-3xl overflow-hidden shadow-md">
                    {{-- Form Pencarian dan Filter akan ditempatkan di sini --}}
                    <label for="search-data-simpanan"
                        class="uppercase bg-black text-white text-center font-semibold w-full px-4 py-2.5">
                        <i class="fas fa-search"></i>
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
                                class="mt-4 w-full h-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md font-semibold">Cari
                                Member</button>
                        </div>
                        <a href="{{ route('simpanan.index') }}"
                            class="bg-blue-400 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-decoration-none text-center font-semibold">Refresh</a>
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
            <div class="w-[80%] bg-white p-4 rounded-3xl shadow-md overflow-hidden">
                {{-- tampil Data Simpanan akan ditempatkan di sini --}}
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                        <i class="fas fa-user-circle mr-2"></i>Informasi Anggota
                    </h3>
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
                                <p class="text-sm text-gray-500">Total Saldo</p>
                                <p class="text-lg font-bold text-red-600">
                                    Rp. <span id="total_pinjaman">-</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <div class="bg-green-600 px-2 shadow-md inline-block rounded-md">
                                    <p class="text-white m-0" id="jenis-pinjaman">none</p>
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
        <hr>
        <!-- Filter dan Search Bar -->
        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-md">
            <div class="flex justify-between items-center 300 px-8 mb-2 mt-2">
                <p class=" bg-slate-400 text-white font-semibold px-4 py-2 text-2xl rounded-md shadow-md mt-2">Histori
                    Simpanan dan
                    Penarikan</p>
                <div class="flex items-center gap-4">
                    <div>
                        <select id="filter-kategori"
                            class="px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="wajib">Wajib</option>
                            <option value="pokok">Pokok</option>
                            <option value="sukarela">Sukarela</option>
                        </select>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Search..." id="search-simpanan"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto flex pb-10 px-10">
                <table class="text-center min-w-full overflow-hidden space-y-4">
                    <thead class="bg-orange-300">
                        <tr
                            class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                            <th>NO.</th>
                            <th>NAMA ANGGOTA</th>
                            <th>KATEGORI</th>
                            <th>TANGGAL</th>
                            <th>NOMINAL/SETORAN</th>
                            <th>KETERANGAN</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($simpanans as $simpanan)
                            <tr class="[&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50 ">
                                <td class="font-medium">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-full">
                                            <div class=" text-gray-900 font-semibold">{{ $simpanan->member->nama_lengkap }}
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
                        @endforeach
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
                        document.getElementById('total_pinjaman').textContent = new Intl.NumberFormat('id-ID')
                            .format(data.total_simpanan);
                        // Update status
                        console.log(data);
                        const statusElem = document.getElementById('jenis-pinjaman');
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
                    option.textContent = `${m.nik} - ${m.nama_lengkap}`;

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
