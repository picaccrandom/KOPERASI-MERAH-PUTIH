@extends('layouts.master')

@section('content')
    <div class="bg-white/40 backdrop-blur-2xl mx-14 py-8 px-24 rounded-2xl">

        <div class="mb-10 border-l-8 border-red-600 pl-4">
            <h1 class="fw-bolder text-shadow-lg text-white uppercase tracking-wider">Formulir <span
                    class="bg-red-600 px-2 rounded-md">Peminjaman</span></h1>
            <hr class="my-0 mb-2">
            <p class="pl-1 text-slate-600">Isi dengan cermat sesuai format dan ketentuan <span
                    class="text-red-400 font-semibold">Peminjaman !</span></p>

        </div>
        <form action="{{ route('pinjaman.store') }}" method="POST"
            class="bg-white px-12 py-12 [&_label]:text-lg [&_label]:pl-1 rounded-md shadow-xl" id="form-pinjaman">
            @csrf
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:text-red-500 after:ml-0.5">Identitas
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
                        class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:text-red-500 after:ml-0.5">Jenis
                        Pinjaman </label>
                    <select name="jenis"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        required>
                        <option value="uang">Uang</option>
                        <option value="barang">Barang</option>
                    </select>
                </div>


            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                <div class="" id="form-uang">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:text-red-500 after:ml-0.5">Jumlah
                        Pinjaman</label>
                    <input type="number" name="jumlah_pinjaman" id="jumlah_pinjaman"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Maksimal Pinjaman ...." min="0" required>
                    <p class="text-red-400 text-xs mt-2 font-semibold" id="info-limit">*Jumlah Pinjaman Maksimal Rp. <span
                            class="text-red-600" id="limit"></span>
                    </p>
                </div>

                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1 after:content-['*'] after:text-red-500 after:ml-0.5">Lama
                        (Bulan)</label>
                    <input type="number" name="tenor"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="12" min="0" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1 ">Bunga per Tahun (%)</label>
                <input type="number" step="0.01" name="bunga"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                    placeholder="1" required value="1">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="catatan" name="catatan"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                    rows="4" placeholder="Tambahkan catatan jika perlu"></textarea>
            </div>

        </form>
        <div class="mt-4 flex justify-end gap-4 font-bold">
            <a href="{{ route('pinjaman.index') }}"
                class="no-underline px-8 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm" >
                Batal
            </a>
            <button type="button" id="btn-simpan" class="px-8 py-2 bg-red-700 hover:bg-red-800 text-white rounded text-sm">
                Simpan
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
                        document.getElementById('jumlah_pinjaman').setAttribute('placeholder', `Maksimal Pinjaman Rp. ${limit.toLocaleString('id-ID')}`);
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
            const jumlahPinjamanValue = document.getElementById('jumlah_pinjaman').value;

            // validate jumlah pinjaman
            if (!memberId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Silahkan pilih member terlebih dahulu dari daftar dropdown.'
                });
                return;
            }

            if (!validateJumlahPinjaman(jumlahPinjamanValue, memberId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pinjaman melebihi limit yang diizinkan untuk member ini.'
                });
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
            const jumlahPinjaman = parseFloat(jumlahPinjamanInput) || 0;
            console.log('Limit:', limit, 'Jumlah Pinjaman:', jumlahPinjaman);

            if (jumlahPinjaman > limit) {
                return false;
            }
            return true;
        }
    </script>
@endsection
