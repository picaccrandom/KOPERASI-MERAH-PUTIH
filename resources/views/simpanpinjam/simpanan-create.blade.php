@extends('layouts.master')

@section('content')
    {{-- @php
        dd($SimpanansPokok);
    @endphp --}}
    <div class="bg-teal-900/40 backdrop-blur-2xl mx-14 py-8 px-24 rounded-2xl">

        <div class="mb-10 border-l-8 border-teal-400 pl-4">
            <h1 class="fw-bolder text-shadow-lg text-white uppercase tracking-wider">Formulir <span
                    class="bg-teal-400 px-2 rounded-md">tambah simpanan</span></h1>
            <hr class="my-0 mb-2">
            <p class="pl-1 text-white">Isi dengan cermat sesuai format dan ketentuan <span
                    class="text-teal-200 font-semibold">Simpanan !</span></p>

        </div>
        <form action="{{ route('simpanan.store') }}" method="POST" id="form-pinjaman"
            class="bg-white px-12 py-12 [&_label]:text-[1rem] [&_label]:pl-1 rounded-md shadow-xl">
            @csrf

            <div class="relative bg-white rounded-lg">
                <div class="p-0"> {{-- Hapus padding berlebih dan hapus tag form kedua di sini --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="mb-1 after:content-['*'] after:text-red-500 after:pl-1">Identitas Member</label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                placeholder="Masukan Nama atau NIK Member ..." name="search-member" id="search-member"
                                required autocomplete="off">
                            <input type="hidden" name="member_id" id="member_id">
                            <div class="dropdown-menu w-[47%] text-base hidden" id="dropdown-member"></div>
                        </div>

                        <div>
                            <label class="mb-1 after:content-['*'] after:text-red-500 after:pl-1">Kategori</label>
                            <select
                                class="text-center w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                name="jenis" id="jenis" required>
                                <option value="">Pilih Jenis</option>
                                <option id="option-pokok" class="hidden" value="Pokok">Pokok</option>
                                <option id="option-wajib" class="hidden" value="Wajib">Wajib</option>
                                <option id="option-sukarela" class="hidden" value="Sukarela">Sukarela</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-4">
                        <div class="col-span-4 row-span-2">
                            <label class="mb-1 after:content-['*'] after:text-red-500 after:pl-1">Nominal/Setoran</label>
                            <div class="flex items-center gap-4 text-5xl h-full relative">
                                <p>Rp. </p>
                                <input type="text"
                                    class="w-full h-full px-3 py-2 bg-slate-400/40 backdrop-blur-2xl relative rounded focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    name="nominal" id="nominal" placeholder="..." autocomplete="off">
                                <div class="absolute top-3 right-5 flex text-xs" id="admin_pay">
                                    <input type="text" name="administrasi" id="administrasi" value="true" hidden
                                        readonly>
                                    <span
                                        class=" px-2 py-1 font-bold text-center  border border-green-400 text-white rounded bg-green-400">+ Rp.
                                        5.000</span>
                                    <i
                                        class="fa-solid fa-x text-red-500 font-bold  hover:bg-slate-50 p-0.5 absolute -top-1 -right-1 text-xs rounded-md px-0.5 "></i>
                                </div>
                            </div>
                            <p id="ket-input-nominal"
                                class="text-red-500 text-xs tracking-widest font-thin pl-28 w-full mt-1 hidden"></p>
                        </div>

                        <div class="col-span-2 h-full row-span-2">
                            <label class="mb-1">Catatan/Keterangan (Opsional)</label>
                            <textarea
                                class="w-full px-3 outline-none py-2 border border-slate-200 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm h-full"
                                rows="6" placeholder="Contoh: Simpanan wajib..." name="catatan"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-4 font-bold">
                <a href="{{ route('simpanan.index') }}"
                    class="no-underline px-8 py-2 border border-gray-300 rounded hover:bg-gray-50 text-sm text-black">
                    Batal
                </a>
                <button type="button" id="btn-simpan"
                    class="px-8 py-2 bg-green-700 hover:bg-green-800 text-white rounded text-sm">
                    Simpan Data Simpanan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const members = @json($members);
        const simpanansPokok = @json($SimpanansPokok);
        const simpanansWajib = @json($SimpanansWajib);

        const nominalInput = document.getElementById('nominal');
        const jenisSelect = document.getElementById('jenis');
        const ketInputNominal = document.getElementById('ket-input-nominal');

        nominalInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });

        nominalInput.addEventListener('blur', function() {
            this.value = formatRupiah(this.value);
        });

        nominalInput.addEventListener('focus', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });

        function formatRupiah(angka) {
            angka = angka.replace(/[^\d]/g, '');
            if (angka === '') return '';
            let sisa = angka.length % 3;
            let rupiah = angka.substr(0, sisa);
            let ribuan = angka.substr(sisa).match(/\d{3}/g);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        }

        jenisSelect.addEventListener('change', function() {
            const selectedJenis = this.value;

            nominalInput.readOnly = false;
            nominalInput.classList.remove('cursor-not-allowed', 'text-gray-500');
            ketInputNominal.classList.add('hidden');

            if (selectedJenis === 'Wajib') {
                nominalInput.value = "20.000";
                nominalInput.readOnly = true;
                nominalInput.classList.add('cursor-not-allowed', 'text-gray-500');
                ketInputNominal.textContent = '*Simpanan Wajib Sejumlah Rp. 20.000';
                ketInputNominal.classList.remove('hidden');

            } else if (selectedJenis === 'Pokok') {
                nominalInput.value = "50.000";
                nominalInput.readOnly = true;
                nominalInput.classList.add('cursor-not-allowed', 'text-gray-500');
                ketInputNominal.textContent = '*Simpanan Pokok Sejumlah Rp. 50.000';
                ketInputNominal.classList.remove('hidden');

            } else if (selectedJenis === 'Sukarela') {
                nominalInput.value = '';
                nominalInput.placeholder = 'Masukkan Nominal Bebas...';
            } else {
                nominalInput.value = '';
            }

        });

        document.getElementById('admin_pay').addEventListener('click', function() {
            const adminToggle = document.getElementById('admin_pay');
            adminToggle.innerHTML = '';
            ketInputNominal.textContent =
                '*Biaya administrasi sebesar Rp. 5000 akan dikenakan untuk simpanan sukarela.';
            ketInputNominal.classList.remove('hidden');
        });

        document.getElementById('search-member').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filtered = members.filter(m =>
                m.nama_lengkap.toLowerCase().includes(query) ||
                m.nik.toLowerCase().includes(query)
            );

            const dropdown = document.getElementById('dropdown-member');
            const memberIdInput = document.getElementById('member_id');

            dropdown.innerHTML = '';

            if (filtered.length > 0 && query !== '') {
                filtered.forEach(m => {
                    const option = document.createElement('a');
                    option.classList.add('dropdown-item', 'cursor-pointer');
                    option.innerHTML = ` <div class="d-flex justify-content-between align-items-center">
                                            <div style="line-height: 1.2;">
                                                <small class="text-danger fw-bold d-block">${m.nik}</small>
                                                <strong class="text-dark text-uppercase font-black" style="font-size: 1rem;">${m.nama_lengkap}</strong>
                                            </div>
                                            <i class="fas fa-user-plus text-muted fa-lg"></i>
                                        </div>`;

                    option.addEventListener('click', function() {
                        document.getElementById('search-member').value = m.nama_lengkap;
                        memberIdInput.value = m.id;
                        dropdown.classList.remove('show', 'hidden');

                        const hasPokok = simpanansPokok.some(s => s.member_id == m.id);
                        const hasWajib = simpanansWajib.some(s => s.member_id == m.id);
                        const optPokok = document.getElementById('option-pokok');
                        const optWajib = document.getElementById('option-wajib');
                        const optSukarela = document.getElementById('option-sukarela');

                        jenisSelect.value = "";
                        nominalInput.value = "";

                        if (hasPokok) {
                            optPokok.style.display = 'none';
                            optWajib.style.display = 'block';
                            optSukarela.style.display = 'block';
                        } else {
                            optPokok.style.display = 'block';
                            optWajib.style.display = 'none';
                            optSukarela.style.display = 'none';
                        }

                        if (hasWajib) {
                            optWajib.style.display = 'none';
                        }
                    });
                    dropdown.appendChild(option);
                });
                dropdown.classList.remove('hidden');
                dropdown.classList.add('show');
            } else {
                dropdown.classList.add('hidden');
            }
        });

        document.getElementById('btn-simpan').addEventListener('click', function() {
            const memberId = document.getElementById('member_id').value;
            const rawNominal = nominalInput.value.replace(/[^\d]/g, '');


            if (!memberId) {
                swal.fire('Error', 'Silahkan pilih member dari daftar yang tersedia.', 'error');
                return;
            }

            if (rawNominal === '' || parseInt(rawNominal) <= 0) {
                swal.fire('Error', 'Nominal simpanan tidak boleh kosong.', 'error');
                return;
            }

            if (jenisSelect.value === '') {
                swal.fire('Error', 'Silahkan pilih jenis simpanan.', 'error');
                return;
            }

            swal.fire({
                title: 'Konfirmasi',
                text: 'Simpan data simpanan ini ke sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    nominalInput.value = rawNominal;
                    document.getElementById('form-pinjaman').submit();
                }
            });
        });
    </script>
@endsection
