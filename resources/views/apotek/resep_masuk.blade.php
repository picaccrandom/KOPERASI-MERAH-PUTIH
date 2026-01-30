@extends('layouts.master')

@section('content')
    {{-- Background menggunakan asset apotek dengan overlay Hijau Emerald --}}
    <div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-apotek.png') }}');">
        <div class="bg-emerald-900/40 backdrop-blur-md min-h-screen p-10">

            {{-- Header Pelayanan Apotek --}}
            <div class="flex justify-between items-end mb-10 border-l-8 border-emerald-500 pl-6 text-white">
                <div>
                    <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-100">
                        Pelayanan <span class="bg-emerald-600 px-3 rounded-lg shadow-lg text-white">Apotek</span>
                    </h1>
                    <p class="text-emerald-100 font-bold mt-2 italic">Dashboard Penjualan Obat Desa Nangsri</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('apotek.gudang') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                        <i class="fas fa-warehouse mr-2"></i> Cek Stok Gudang
                    </a>
                    <a href="{{ route('apotek.index') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                        <i class="fa-solid fa-book mr-2"></i> Jual Obat
                    </a>
                </div>
            </div>

            {{-- Container Tabel Hijau --}}
            <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
                <div
                    class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                    <span><i class="fas fa-pills mr-2"></i> Resep Obat Masuk</span>
                    <span class="bg-white text-emerald-600 px-4 py-1 rounded-full text-xs shadow-inner font-black">
                        Tersedia: {{ $resepMasuks->count() }} Orderan Obat
                    </span>
                </div>

                <div class="p-8">
                    <table class="w-full text-left">
                        <thead
                            class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest text-center">
                            <tr>
                                <th class="py-4">PENDAFTARAN ID</th>
                                <th class="py-4">Nama Pasien</th>
                                <th class="py-4">Catatan</th>
                                <th class="py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50 font-bold text-center">
                            @forelse($resepMasuks as $r)
                                <tr class="hover:bg-emerald-50/50 transition-colors text-slate-700 group">
                                    <td class="py-4 font-mono text-emerald-600">{{ $r->kode_pendaftaran }}</td>
                                    <td class="py-4 uppercase tracking-tighter">{{ $r->member->nama_lengkap }}</td>
                                    <td class="py-4 uppercase tracking-tighter">{{ $r->resep_obat }}</td>
                                    <td class="py-4 text-center">
                                        {{-- Tombol Aktif Jual Obat Membuka Modal --}}
                                        <button onclick="openJualModal({{ $r->pendaftaranKlinik->rekamMedis->id }})"
                                            class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase shadow-md hover:bg-emerald-700 transition-all hover:scale-105 active:scale-95">
                                            <i class="fas fa-shopping-cart mr-1"></i> Unduh Resep
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                        <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                        Tidak ada resep obat masuk.<br>
                                        <span class="text-xs uppercase not-italic text-emerald-600 font-black">Silakan
                                            lakukan mutasi stok dari gudang apotek</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TRANSAKSI PENJUALAN --}}
    <div id="modalJualObat"
        class="hidden absolute top-28 inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white/95 px-10 rounded-2xl shadow-2xl mx-auto max-w-5xl  overflow-hidden border border-blue-100">
            <div class="bg-blue-600 p-6 text-white">
                <h3 class="text-xl font-black uppercase tracking-widest flex items-center">
                    <i class="fas fa-user-md mr-3 text-2xl"></i> Pemeriksaan Medis (EMR)
                </h3>
                <div class="mt-2 text-sm opacity-90 font-bold">
                    PASIEN: <span id="nama_pasien"></span>| NO. REG: <span id="no_reg"></span>
                </div>
            </div>

            <form>
                @csrf

                <input type="hidden" name="kode_transaksi" value="">
                <div class="flex flex-col gap-6 p-10">
                    <div class="flex flex-row gap-6">
                        {{-- Info Keluhan Awal --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <div>
                                <label class="block text-xs font-black text-blue-600 uppercase tracking-wider mb-1">Keluhan
                                    Pasien</label>
                                <p id="keluhan_pasien" class="text-slate-700 font-bold italic">""</p>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-blue-600 uppercase tracking-wider mb-1">Tensi
                                    Darah</label>
                                <p id="tensi_darah" class="text-slate-700 font-bold">- </p>
                            </div>
                        </div>

                        <hr class="border-dashed border-slate-200">

                        <div class="">
                            {{-- Input Diagnosa --}}
                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Diagnosa
                                    Medis</label>
                                <textarea name="diagnosa" id="diagnosa" required rows="3" readonly
                                    class="w-full p-2 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all"
                                    placeholder="Tuliskan hasil diagnosa dokter di sini..."></textarea>
                            </div>

                            {{-- Input Tindakan --}}
                            <div>
                                <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Tindakan
                                    /
                                    Terapi</label>
                                <textarea name="tindakan" id="tindakan" required rows="3" readonly
                                    class="w-full p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all"
                                    placeholder="Tuliskan tindakan atau obat yang diberikan..."></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- input resep obat -->
                    <div class="">
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Resep
                            Obat</label>
                        <div class="mt-2" id="obatArea">
                            {{-- obat area --}}
                            <div class="flex justify-between items-center  gap-4">
                                <select name="resep_obat[]" id="resep_obat[]"
                                    class="w-full text-sm font-black text-slate-700 p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all">
                                    <option value="" disabled selected>Pilih Obat...</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->kode_obat }}">{{ $obat->nama_obat }} - Stok:
                                            {{ $obat->stok_apotek }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="qty[]" id="qty[]" min="1" placeholder="Qty"
                                    value="1"
                                    class=" w-[30%] text-sm font-black text-slate-700 p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all">
                                <button type="button"
                                    class="removeObatBtn px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="tambahObatBtn" onclick="tambahAreaObat()"
                            class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Tambah Obat Lain
                        </button>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-4 pt-6 p-4">
                    <button type="button" onclick="closeModal()"
                        class="px-8 py-3 bg-slate-500 text-white rounded-xl font-black uppercase hover:bg-slate-600 transition-all shadow-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-10 py-3 bg-blue-600 text-white rounded-xl font-black uppercase hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all transform hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i> Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentObat = null;
        const obats = @json($obats);
        const rekamMedis = @json($rekamMedis);

        function openJualModal(id) {
            const data = rekamMedis.find(r => r.id === id);
            console.log(data);
            document.getElementById('modalJualObat').classList.remove('hidden');
            document.getElementById('nama_pasien').innerText = data.pendaftaran_klinik.member.nama_lengkap;
            document.getElementById('no_reg').innerText = data.pendaftaran_klinik.no_registrasi;
            document.getElementById('keluhan_pasien').innerText = `"${data.pendaftaran_klinik.keluhan}"`;
            document.getElementById('tensi_darah').innerText = data.pendaftaran_klinik.tensi;
            document.getElementById('diagnosa').value = data.diagnosa;
            document.getElementById('tindakan').value = data.tindakan;
        }

        function hitungTotal() {
            const qty = document.getElementById('inputQty').value;
            const total = qty * currentObatDetails.harga_jual;
            document.getElementById('displayTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        function closeModal() {
            document.getElementById('modalJualObat').classList.add('hidden');
        }

        function tambahAreaObat(){
            const obatArea = document.getElementById('obatArea');
            const newObatDiv = document.createElement('div');
            newObatDiv.classList.add('flex', 'justify-between', 'items-center', 'gap-4', 'mt-4');
            newObatDiv.innerHTML = `
                <select name="resep_obat[]" class="w-full text-sm font-black text-slate-700 p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all">
                    <option value="" disabled selected>Pilih Obat...</option>
                    @foreach ($obats as $obat)
                        <option value="{{ $obat->kode_obat }}">{{ $obat->nama_obat }} - Stok: {{ $obat->stok_apotek }}</option>
                    @endforeach
                </select>
                <input type="number" name="qty[]" min="1" placeholder="Qty" value="1    " class=" w-[30%] text-sm font-black text-slate-700 p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all">
                <button type="button" class="removeObatBtn px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            obatArea.appendChild(newObatDiv);

            // Tambah event listener untuk tombol hapus
            newObatDiv.querySelector('.removeObatBtn').addEventListener('click', function() {
                obatArea.removeChild(newObatDiv);
            });
        }
    </script>
@endsection
