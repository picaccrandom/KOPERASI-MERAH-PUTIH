@extends('layouts.master')

@section('content')
    <div class="bg-orange-900/40 backdrop-blur-2xl mx-12 py-8 px-12 rounded-2xl">

        <div class="mb-10 border-l-8 border-orange-400 pl-4">
            <h1 class="fw-bolder text-shadow-lg text-white uppercase tracking-wider">Formulir <span
                    class="bg-orange-400 px-2 rounded-md">Ajuan Peminjaman</span></h1>
            <hr class="my-0 mb-2">
            <p class="pl-1 text-white">Isi dengan cermat sesuai format dan ketentuan <span
                    class="text-orange-400 font-semibold">Peminjaman !</span></p>

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
                    <label class="block font-medium text-gray-700 mb-2 text-2xl">Keterangan</label>
                    <ul class="text-sm flex justify-between gap-2.5 text-center bg-slate-400/20 rounded-md shadow p-2">
                        <li>Status Anggota <hr class="my-1"><span class=" font-semibold uppercase" id="status-member">none</span></li>
                        <li>Saldo Simpanan (Sukarela)  <hr class="my-1"><span id="saldo-simpanan" class="text-red-500">Rp. 0,00</span></li>
                        <li>Riwayat Pinjaman  <hr class="my-1"><span id="riwayat-pinjaman" class="text-orange-500">- kali</span></li>
                    </ul>
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
                    <div class="flex justify-center gap-4 mt-2">
                        <label for="tenor-3" class="flex justify-center items-center">
                            <input type="radio" name="tenor" id="tenor-3" value="3" class="mr-1 sr-only peer"
                                checked>
                            <p class="peer-checked:bg-red-600 peer-checked:text-white px-3 py-0.5 rounded-md border border-gray-300"
                                id="tenor-3-value">
                                3 bulan
                            </p>
                        </label>
                        <label for="tenor-6" class="flex justify-center items-center">
                            <input type="radio" name="tenor" id="tenor-6" value="6" class="mr-1 sr-only peer">
                            <p class="peer-checked:bg-red-600 peer-checked:text-white px-3 py-0.5 rounded-md border border-gray-300"
                                id="tenor-6-value">
                                6 bulan
                            </p>
                        </label>
                        <label for="tenor-12" class="flex justify-center items-center">
                            <input type="radio" name="tenor" id="tenor-12" value="12" class="mr-1 sr-only peer">
                            <p class="peer-checked:bg-red-600 peer-checked:text-white px-3 py-0.5 rounded-md border border-gray-300"
                                id="tenor-12-value">
                                12 bulan
                            </p>
                        </label>
                        <label for="tenor-24" class="flex justify-center items-center">
                            <input type="radio" name="tenor" id="tenor-24" value="24" class="mr-1 sr-only peer">
                            <p class="peer-checked:bg-red-600 peer-checked:text-white px-3 py-0.5 rounded-md border border-gray-300"
                                id="tenor-24-value">
                                24 bulan
                            </p>
                        </label>
                        <label for="tenor-ot" class="flex justify-center items-center">
                            <input type="radio" name="tenor" id="tenor-ot" value="other" class="mr-1 sr-only peer">
                            <p class="peer-checked:bg-red-600 peer-checked:text-white px-3 py-0.5 rounded-md border border-gray-300"
                                id="tenor-ot-value">
                                Lainnya
                            </p>
                        </label>
                    </div>
                    <input type="number" name="tenor_custom" id="tenor-other"
                        class="w-full mt-2 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm hidden"
                        placeholder="Masukan lama bulan ... (min 1 bulan)" min="1" autocomplete="off">
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
            <button type="button" id="btn-simpan"
                class="px-8 py-2 bg-red-700 hover:bg-red-800 text-white rounded text-sm">
                Simpan Data Pinjaman
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Pinjaman data
        const members = @json($members);
        const pinjamans = @json($pinjamans);
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
                        // tangkap limit
                        const found = limitAnggotas.find(l => Number(l.member_id) === Number(
                            selectedId));
                        // tangkap saldo simpanan data member
                        const saldoSimpanan = pinjamans.filter(p => Number(p.member_id) === Number(selectedId))
                            .reduce((total, p) => {
                                const saldoSukarela = (p.simpanan_details || [])
                                            .filter(d => d.jenis === 'sukarela')
                                            .reduce((subTotal, d) => subTotal + Number(d.saldo), 0);

                                        return total + saldoSukarela;
                                    }, 0);                        

                        // tangkap riwayat peminjaman
                        const riwayatPinjaman = pinjamans.filter(p => Number(p.member_id) === Number(selectedId))
                            .filter(p => p.COA === 'Pinjam').length;
                        const limit = found ? Number(found.limit) : 0;
                        limitSpan.textContent = limit.toLocaleString('id-ID');
                        // tangkap status member
                        if(m.status == 'aktif'){
                            document.getElementById('status-member').classList.remove('text-red-600');
                            document.getElementById('status-member').classList.add('text-green-600');
                        } else {
                            document.getElementById('status-member').classList.remove('text-green-600');
                            document.getElementById('status-member').classList.add('text-red-600');
                        }
                        document.getElementById('jumlah_pinjaman').setAttribute('max', limit);
                        document.getElementById('jumlah_pinjaman').setAttribute('placeholder',
                        `Maks ${limit.toLocaleString('id-ID')}`);
                        // add keterangan member
                        document.getElementById('status-member').innerHTML = m.status;
                        document.getElementById('saldo-simpanan').textContent = `Rp. ${Number(saldoSimpanan).toLocaleString('id-ID')},00`;
                        document.getElementById('riwayat-pinjaman').textContent = `${riwayatPinjaman} kali`;

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

        document.querySelectorAll('input[name="tenor"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const otherInput = document.getElementById('tenor-other');

                if (this.value === 'other') {
                    otherInput.classList.remove('hidden');
                    otherInput.setAttribute('required', 'required');
                } else {
                    otherInput.classList.add('hidden');
                    otherInput.removeAttribute('required');
                    otherInput.value = ''; // optional: reset nilai
                }
            });
        });

        document.getElementById('btn-simpan').addEventListener('click', function() {
            const memberId = memberIdInput.value;
            const jumlahPinjamanInput = document.getElementById('jumlah_pinjaman').value;
            const jumlahPinjamanValue = parseFloat(jumlahPinjamanInput.replace(/[^\d]/g, ''));
            const lamabayarInput = document.querySelector('input[name="tenor"]:checked').value || document.getElementById('tenor-other').value;
            const otherInput = document.getElementById('tenor-other');
            document.getElementById('tenor-ot').value = otherInput.value;

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

            if (jumlahPinjamanValue === '' || Number(jumlahPinjamanValue) <= 0 || isNaN(jumlahPinjamanValue)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Jumlah pinjaman harus diisi dan lebih dari 0.'
                });
                return;
            }

            if (Number(lamabayarInput) <= 0 || lamabayarInput === '') {
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
