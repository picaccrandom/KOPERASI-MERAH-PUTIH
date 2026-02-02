@extends('layouts.master')

@section('content')
    <div class="bg-white/40 backdrop-blur-2xl mx-12 py-8 px-12 rounded-2xl">

        <div class="mb-10 border-l-8 border-red-600 pl-4">
            <h1 class="fw-bolder text-shadow-lg text-white uppercase tracking-wider">Formulir <span
                    class="bg-red-600 px-2 rounded-md">Peminjaman</span></h1>
            <hr class="my-0 mb-2">
            <p class="pl-1 text-slate-600">Isi dengan cermat sesuai format dan ketentuan <span
                    class="text-red-400 font-semibold">Peminjaman !</span></p>

        </div>
        <form action="{{ route('pinjaman.store') }}" method="POST"
            class="bg-white px-12 py-12 pb-16 [&_label]:text-[1rem] [&_label]:pl-1 rounded-md shadow-xl" id="form-pinjaman">
            @csrf
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <div>
                    <label
                        class="block font-medium text-gray-700 mb-2 text-2xl after:content-['*'] after:text-red-500 after:ml-0.5">Identitas
                        Member</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Masukan Nama atau NIK Member ..." name="search-member" id="search-member" required
                        autocomplete="off">
                    <input type="hidden" name="member_id" id="member_id" value="">
                    <div class="dropdown-menu w-[40%] text-base hidden" id="dropdown-member"></div>
                </div>

                <div>
                    <label
                        class="block font-medium text-gray-700 mb-2 text-2xl">Jenis
                        Pinjaman </label>
                        <input type="text" name="jenis_pinjaman" id="jenis_pinjaman" value="Uang" readonly
                            class="w-full px-3 py-2 border uppercase text-center bg-slate-300 text-white font-semibold tracking-wider  border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                </div>


            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

                <div class="mb-4">
                    <label class="block text-xs font-medium mb-2 bg-orange-400 px-1.5 text-white ">Bunga per Tahun
                        (%)</label>
                    <input type="number" step="0.01" name="bunga"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="1" required value="1">
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-2 after:content-['*'] after:text-red-500 after:ml-0.5">Lama
                        (Bulan)</label>
                    <input type="number" name="tenor" id="tenor"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="12" min="0" required>
                </div>
            </div>

            <div class="grid grid-cols-6  gap-4 mb-2">
                <div class="col-span-4" id="form-uang">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-2 after:content-['*'] after:text-red-500 after:ml-0.5">Jumlah
                        Pinjaman</label>
                    <div class="flex items-center gap-4 text-5xl h-full">
                        <p>Rp. </p>
                        <input type="text" name="jumlah_pinjaman" id="jumlah_pinjaman"
                            class="h-full w-full px-3 py-2 bg-slate-500/40 backdrop-blur-2xl rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                            placeholder="...." min="0" required autocomplete="off">
                    </div>
                    <p class="pl-28 text-red-400 text-xs mt-2 font-semibold" id="info-limit">*Jumlah Pinjaman Maksimal Rp.
                        <span class="text-red-600" id="limit"></span>
                    </p>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" name="catatan"
                        class="h-full w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        rows="5" placeholder="Tambahkan catatan jika perlu"></textarea>
                </div>
            </div>

        </form>
        <div class="mt-4 flex justify-end gap-4 font-bold">
            <a href="{{ route('pinjaman.index') }}"
                class="no-underline px-8 py-2 border-2 border-white rounded hover:bg-white text-white text-sm text-decoration-none hover:!text-black">
                Batal
            </a>
            <button type="button" id="btn-simpan" class="px-8 py-2 bg-red-700 hover:bg-red-800 text-white rounded text-sm">
                Simpan Data Pinjaman
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Pinjaman data
        const members = @json($members);
        const limitAnggotas = @json($limitAnggotas);

        // element refs
        const memberIdInput = document.getElementById('member_id');
        const limitSpan = document.getElementById('limit');

        // inisialisasi elemen input nominal
        const nominalInput = document.getElementById('jumlah_pinjaman');

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
        


        // tampilkan search member
        document.getElementById('search-member').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filtered = members.filter(m =>
                m.nama_lengkap.toLowerCase().includes(query) ||
                m.nik.toLowerCase().includes(query)
            );
            const dropdown = document.getElementById('dropdown-member');
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
                        const found = limitAnggotas.find(l => Number(l.member_id) === Number(
                            selectedId));
                        const limit = found ? Number(found.limit) : 0;
                        limitSpan.textContent = limit.toLocaleString('id-ID');
                        document.getElementById('jumlah_pinjaman').setAttribute('max', limit);
                        document.getElementById('jumlah_pinjaman').setAttribute('placeholder',
                            `Maks ${limit.toLocaleString('id-ID')}`);
                    });
                    dropdown.appendChild(option);
                });
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        });


        // tampilkan limit pinjaman sesuai member (trigger pada change jika diperlukan)
        document.getElementById('search-member').addEventListener('change', function() {
            const memberId = memberIdInput.value;
            if (!memberId) {
                // document.getElementById('info-limit').textContent = '*Anggota tidak ditemukan.';
            }
        });

        document.getElementById('btn-simpan').addEventListener('click', function() {
            const memberId = memberIdInput.value;
            const jumlahPinjamanInput = document.getElementById('jumlah_pinjaman').value;
            const jumlahPinjamanValue = parseFloat(jumlahPinjamanInput.replace(/[^\d]/g, ''));
            const lamabayarInput = document.getElementById('tenor').value;
            
            // validate jumlah pinjaman
            if (!memberId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Silahkan pilih member terlebih dahulu dari daftar dropdown.'
                });
                return;
            }

            console.log('Jumlah Pinjaman Input:', jumlahPinjamanValue);
            
            if(jumlahPinjamanValue === '' || Number(jumlahPinjamanValue) <= 0 || isNaN(jumlahPinjamanValue)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pinjaman harus diisi dan lebih dari 0.'
                });
                return;
            }

            if(Number(lamabayarInput) <= 0 || lamabayarInput === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Lama bayar harus diisi setidaknya minimal 1 bulan.'
                });
                return;
            }

            if (!validateJumlahPinjaman(jumlahPinjamanValue, memberId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pinjaman melebihi limit yang diizinkan untuk member ini.'
                });
                document.getElementById('jumlah_pinjaman').value = limitAnggotas.find(l => Number(l.member_id) ===
                    Number(memberId)).limit;
                return;
            }


            // konfirmasi sebelum submit
            Swal.fire({
                title: 'Konfirmasi Peminjaman',
                text: "Pastikan data PINJAMAN sudah benar dan valid!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, sudah benar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('jumlah_pinjaman').value = jumlahPinjamanValue;
                    const btn = document.getElementById('btn-simpan');
                    btn.disabled = true;
                    btn.innerText = 'Menyimpan...';
                    document.getElementById('form-pinjaman').submit();
                }
            });
        });


        function validateJumlahPinjaman(jumlahPinjamanInput, memberId) {
            const limitData = limitAnggotas.find(l => Number(l.member_id) === Number(memberId));
            const limit = limitData ? Number(limitData.limit) : 1000000;
            const jumlahPinjaman = jumlahPinjamanInput || 0;
            console.log('Limit:', limit, 'Jumlah Pinjaman:', jumlahPinjaman);

            if (jumlahPinjaman > limit) {
                return false;
            }
            return true;
        }
    </script>
@endsection
