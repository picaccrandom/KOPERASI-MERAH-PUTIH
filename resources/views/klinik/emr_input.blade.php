@extends('layouts.master')

@section('content')
    <div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-klinik.png') }}');">
        <div class="bg-blue-900/10 backdrop-blur-md min-h-screen p-10">

            <div class="bg-white/95 rounded-2xl shadow-2xl mx-auto max-w-4xl overflow-hidden border border-blue-100">
                {{-- Header Form EMR --}}
                <div class="bg-blue-600 p-6 text-white">
                    <h3 class="text-xl font-black uppercase tracking-widest flex items-center">
                        <i class="fas fa-user-md mr-3 text-2xl"></i> Pemeriksaan Medis (EMR)
                    </h3>
                    <div class="mt-2 text-sm opacity-90 font-bold">
                        PASIEN: {{ $pasien->member->nama_lengkap }} | NO. REG: {{ $pasien->no_registrasi }}
                    </div>
                </div>

                <form action="{{ route('klinik.simpanTindakan', $pasien->id) }}" method="POST" class="p-10 space-y-6">
                    @csrf

                    <input type="hidden" name="member_id" value="{{ $pasien->member_id }}">

                    {{-- Info Keluhan Awal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <div>
                            <label class="block text-xs font-black text-blue-600 uppercase tracking-wider mb-1">Keluhan
                                Pasien</label>
                            <p class="text-slate-700 font-bold italic">"{{ $pasien->keluhan }}"</p>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-blue-600 uppercase tracking-wider mb-1">Tensi
                                Darah</label>
                            <p class="text-slate-700 font-bold">{{ $pasien->tensi ?? '-' }} mmHg</p>
                        </div>
                    </div>

                    <hr class="border-dashed border-slate-200">

                    {{-- Input Diagnosa --}}
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Diagnosa
                            Medis</label>
                        <textarea name="diagnosa" required rows="3"
                            class="w-full p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all"
                            placeholder="Tuliskan hasil diagnosa dokter di sini..."></textarea>
                    </div>

                    {{-- Input Tindakan --}}
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Tindakan /
                            Terapi</label>
                        <textarea name="tindakan" required rows="3"
                            class="w-full p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all"
                            placeholder="Tuliskan tindakan atau obat yang diberikan..."></textarea>
                    </div>
                    {{-- input biaya tindakan --}}
                    <div>
                        <label class="block text-sm font-black text-slate-700 mb-2 uppercase tracking-wide">Biaya
                            Tindakan</label>
                        <div class="flex items-center  gap-4">
                            Rp.
                            <input type="number" name="biaya_tindakan" required min="1"
                                class="w-full p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all"
                                placeholder="Masukkan biaya tindakan..."></textarea>
                        </div>
                    </div>

                    <!-- input resep obat -->
                    <div class="mt-6">
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
                                <input type="number" name="qty[]" id="qty[]" min="1" placeholder="Qty" value="1"
                                    class=" w-[30%] text-sm font-black text-slate-700 p-4 border border-slate-300 rounded-xl focus:ring-4 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition-all">
                                <button type="button" class="removeObatBtn   text-red-600 rounded-lg hover:scale-110">
                                    <i class="fas fa-trash-alt text-2xl"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" id="tambahObatBtn" onclick="tambahAreaObat()" class="mt-2 px-4 py-2  text-blue-600  hover:underline hover:underline-offset-2 transition-all font-black flex items-center">
                            <i class="fas fa-plus mr-2"></i> Tambah Obat Lain
                        </button>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end gap-4 pt-6">
                        <a href="{{ route('klinik.index') }}"
                            class="px-8 py-3 bg-slate-500 text-white rounded-xl font-black uppercase hover:bg-slate-600 transition-all shadow-lg">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-10 py-3 bg-blue-600 text-white rounded-xl font-black uppercase hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all transform hover:-translate-y-1">
                            <i class="fas fa-save mr-2"></i> Simpan Rekam Medis
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection


@section('scripts')
    <script>
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
                <button type="button" class="removeObatBtn text-red-600 rounded-lg hover:scale-110 transition-all duration-500">
                    <i class="fas fa-trash-alt text-2xl"></i>
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
