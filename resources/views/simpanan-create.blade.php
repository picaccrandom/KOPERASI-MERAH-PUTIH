@extends('layouts.master')

@section('content')
    {{-- @php
        dd($SimpanansPokok);
    @endphp --}}
    <div class="bg-white/40 backdrop-blur-2xl mx-14 py-8 px-24 rounded-2xl">

        <div class="mb-10 border-l-8 border-green-600 pl-4">
            <h1 class="fw-bolder text-shadow-lg text-white uppercase tracking-wider">Formulir <span
                    class="bg-green-600 px-2 rounded-md">simpanan</span></h1>
            <hr class="my-0 mb-2">
            <p class="pl-1 text-slate-600">Isi dengan cermat sesuai format dan ketentuan <span
                    class="text-green-600 font-semibold">Simpanan !</span></p>

        </div>
        <form action="{{ route('simpanan.store') }}" method="POST"
            class="bg-white px-12 py-12 [&_label]:text-[1rem] [&_label]:pl-1 rounded-md shadow-xl" id="form-pinjaman">
            @csrf
            <div class="relative bg-white rounded-lg">

                <div class="p-6">
                    <form action="{{ route('simpanan.store') }}" method="POST"
                        class="[&_label]:text-lg [&_label]:font-medium [&_label]:text-slate-400">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class=" mb-1 after:content-['*'] after:text-red-500 after:pl-1">Identitas
                                    Member</label>
                                <input type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    placeholder="Masukan Nama atau NIK Member ..." name="search-member" id="search-member"
                                    required autocomplete="off">
                                <input type="hidden" name="member_id" id="member_id" value="">
                                <div class="dropdown-menu w-[47%] text-base hidden" id="dropdown-member"></div>
                            </div>

                            <div>
                                <label class=" mb-1 after:content-['*'] after:text-red-500 after:pl-1">Kategori</label>
                                <select
                                    class="text-center w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    name="jenis" id="jenis" required>
                                    <option class="hidden" id="jenis-option" value="">Pilih Jenis</option>
                                    <option class="" id="option-wajib" value="Wajib">Wajib</option>
                                    <option class="" id="option-pokok" value="Pokok">Pokok</option>
                                    <option class="" id="option-sukarela" value="Sukarela">Sukarela</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-4 row-span-2">
                                <label
                                    class=" mb-1 after:content-['*'] after:text-red-500 after:pl-1">Nominal/Setoran</label>
                                <div class="flex items-center gap-4 text-5xl h-full">
                                    <p>Rp. </p>
                                    <input type="text"
                                        class="w-full h-full px-3 py-2 bg-slate-400/40 backdrop-blur-2xl rounded focus:ring-2  focus:ring-red-500 focus:border-red-500"
                                        name="nominal" id="nominal" min="0" placeholder="..." value=""
                                        autocomplete="off">
                                </div>
                                <p id="ket-input-nominal"
                                    class="text-red-500 text-xs tracking-widest font-thin pl-28 w-full mt-1 hidden">
                                    *Maksimal Rp. 50.000</p>
                            </div>

                            <div class="col-span-2 h-full row-span-2">
                                <label class="mb-1">Catatan/Keterangan (Opsional)</label>
                                <textarea
                                    class="w-full px-3 outline-none py-2 mb-0  border border-slate-200 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm h-full"
                                    rows="6" placeholder="Contoh: Simpanan wajib akhir bulan/gaji karyawan" name="catatan"></textarea>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-4 font-bold">
                <a href="{{ route('simpanan.index') }}"
                    class="no-underline px-8 py-2 border border-gray-300 rounded hover:bg-gray-50 text-sm text-decoration-none text-white hover:text-black">
                    Batal
                </a>
                <button type="button" id="btn-simpan"
                    class="px-8 py-2 bg-green-700 hover:bg-green-800 text-white rounded text-sm">
                    Simpan Data Simpanan
                </button>
            </div>
    </div>
@endsection

@section('scripts')
    <script>
        const members = @json($members);
        const simpanansPokok = @json($SimpanansPokok);

        // inisialisasi elemen input nominal
        const nominalInput = document.getElementById('nominal');

        // cegah semua inputan selain angka
        nominalInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });

        // ubah format saat input kehilangan fokus
        nominalInput.addEventListener('blur', function() {
            this.value = formatRupiah(this.value);
        });

        // hapus format saat mau edit
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

        const jenisSelect = document.getElementById('jenis');
        const ketInputNominal = document.getElementById('ket-input-nominal');

        jenisSelect.addEventListener('change', function() {

            const selectedJenis = this.value;
            let nominalPlaceholder = '';

            if (selectedJenis === 'Wajib') {
                nominalPlaceholder = '20000';
                ketInputNominal.textContent = '*Maksimal Rp. 20.000';
                nominalInput.value = "20000";
                nominalInput.readonly = true;
                nominalInput.classList.add('cursor-not-allowed');
                nominalInput.classList.add('text-gray-500');
                ketInputNominal.classList.remove('hidden');

            } else if (selectedJenis === 'Pokok') {
                nominalPlaceholder = '50000';
                ketInputNominal.textContent = '*Simpanan Pokok Sejumlah Rp. 50.000';
                nominalInput.value = "50000";
                nominalInput.readonly = true;
                nominalInput.classList.add('cursor-not-allowed');
                nominalInput.classList.add('text-gray-500');
                ketInputNominal.classList.remove('hidden');
            } else if (selectedJenis === 'Sukarela') {
                ketInputNominal.classList.add('hidden');
                nominalInput.readonly = false;
                nominalInput.classList.remove('cursor-not-allowed');
                nominalInput.classList.remove('text-gray-500');
                nominalInput.value = '';
            } else {
                nominalInput.value = '';
                ketInputNominal.classList.add('hidden');
                nominalInput.readonly = false;
                nominalInput.classList.remove('cursor-not-allowed');
                nominalInput.classList.remove('text-gray-500');
            }

            nominalInput.placeholder = nominalPlaceholder;
        });


        // tampilkan search member
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
                    option.textContent = `${m.nik} - ${m.nama_lengkap}`;
                    option.setAttribute('data-id', m.id);
                    option.setAttribute('data-nama', m.nama_lengkap);
                    option.addEventListener('click', function() {
                        const selectedId = this.getAttribute('data-id');
                        const selectedName = this.getAttribute('data-nama');
                        document.getElementById('search-member').value = selectedName;
                        memberIdInput.value = selectedId;
                        dropdown.classList.remove('show');


                        // Check apakah member sudah punya simpanan pokok
                        const hasPokok = simpanansPokok.some(s =>  s.simpanan && s.simpanan.member_id == selectedId);
                        console.log('Member ID:', selectedId, 'Has Pokok:', hasPokok);
                        if (hasPokok) {
                            document.getElementById('option-pokok').classList.add('hidden');
                        } else {
                            document.getElementById('option-pokok').classList.remove('hidden');
                        }
                        
                        if(!hasPokok){
                            document.getElementById('option-wajib').classList.add('hidden');
                            document.getElementById('option-sukarela').classList.add('hidden');
                        }else{
                            document.getElementById('option-wajib').classList.remove('hidden');
                            document.getElementById('option-sukarela').classList.remove('hidden');
                        }
                    });
                    dropdown.appendChild(option);
                });
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        });

        // tombol simpan
        document.getElementById('btn-simpan').addEventListener('click', function() {
            const memberId = document.getElementById('member_id').value;

            if (!memberId) {
                swal.fire('Error', 'Silahkan pilih member dari daftar yang tersedia.', 'error');
                return;
            }

            if (nominalInput.value === null || nominalInput.value === '' || parseInt(nominalInput.value.replace(
                    /[^\d]/g, '')) <= 0) {
                swal.fire('Error', 'Nominal simpanan tidak boleh kosong atau nol.', 'error');
                return;
            }

            swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan data simpanan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, sudah benar!',
                cancelButtonText: 'Batal'
            }).then((willSave) => {
                if (willSave) {
                    document.getElementById('nominal').value = parseFloat(nominalInput.value.replace(
                        /[^\d]/g, ''));
                    document.getElementById('form-pinjaman').submit();
                }
            });
        });


        // fungsi mengecek apakah member sudah punya simpanan pokok
        const hasPokok = simpanansPokok.some(
            s => s.transaksi_sp && s.transaksi_sp.member_id == selectedId
        );

    </script>
@endsection
