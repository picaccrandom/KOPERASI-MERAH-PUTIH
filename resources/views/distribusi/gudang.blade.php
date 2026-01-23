@extends('layouts.master')

@section('content')
{{-- Background Logistik Central Hub --}}
<div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-distribusi.png') }}');">
    <div class="bg-amber-900/40 backdrop-blur-md min-h-screen p-10">
        
        {{-- Header Gudang Distribusi --}}
        <div class="flex justify-between items-end mb-10 border-l-8 border-amber-500 pl-6 text-white">
            <div>
                <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-100">
                    Gudang <span class="bg-amber-600 px-3 rounded-lg shadow-lg text-white">Distribusi</span>
                </h1>
                <p class="text-amber-100 font-bold mt-2 italic text-sm italic">Central Hub - Logistik Koperasi Merah Putih</p>
            </div>
            {{-- Tombol Barang Masuk Partai Besar --}}
            <button onclick="toggleModal('modalBarangMasuk')" class="bg-amber-600 hover:bg-amber-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest flex items-center text-white">
                <i class="fas fa-truck-loading mr-2 text-xl"></i> Barang Masuk
            </button>
        </div>

        {{-- Tabel Inventaris Skala Besar --}}
        <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-amber-100">
            <div class="bg-amber-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-boxes mr-2"></i> Inventaris Stok Skala Besar</span>
                <span class="bg-white/20 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">Skala: Karung / Ton</span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-amber-700 border-b-2 border-amber-100 font-black uppercase text-sm tracking-widest">
                        <tr>
                            <th class="py-4">KODE</th>
                            <th class="py-4">NAMA BARANG</th>
                            <th class="py-4 text-center">STOK PUSAT</th>
                            <th class="py-4 text-center">OPSI LOGISTIK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-50 font-bold text-slate-700">
                        @forelse($barangs as $b)
                        <tr class="hover:bg-amber-50/50 transition-colors">
                            <td class="py-4 font-mono text-amber-600">{{ $b->kode_barang }}</td>
                            <td class="py-4 uppercase tracking-tighter">{{ $b->nama_barang }}</td>
                            <td class="py-4 text-center">
                                <span class="px-4 py-1 bg-amber-100 text-amber-700 rounded-xl border border-amber-200">
                                    {{ $b->stok_pusat }} {{ $b->satuan_besar }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    {{-- Tombol Skema 1: Luar Desa --}}
                                    <button onclick="openFakturModal({{ json_encode($b) }})" class="bg-amber-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-md hover:bg-amber-700 transition-all active:scale-95">
                                        <i class="fas fa-file-invoice mr-1 text-white"></i> Buat Faktur
                                    </button>
                                    {{-- Tombol Skema 2: Mutasi Internal --}}
                                    <button onclick="openMutasiModal({{ json_encode($b) }})" class="bg-slate-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-md hover:bg-slate-800 transition-all active:scale-95">
                                        <i class="fas fa-exchange-alt mr-1 text-white"></i> Mutasi Gerai
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide uppercase opacity-50">
                                <i class="fas fa-warehouse text-6xl mb-4 block"></i>
                                Gudang Pusat Belum Memiliki Stok
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 1: BARANG MASUK --}}
<div id="modalBarangMasuk" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-amber-100">
        <div class="bg-amber-600 p-6 text-white text-center">
            <h3 class="text-xl font-black uppercase tracking-widest"><i class="fas fa-download mr-2"></i> Input Stok Karungan</h3>
        </div>
        <form action="{{ route('distribusi.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">Kode Barang</label>
                    <input type="text" name="kode_barang" required class="w-full border-b-2 border-amber-100 p-2 outline-none focus:border-amber-600 font-bold uppercase">
                </div>
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">Nama Barang</label>
                    <input type="text" name="nama_barang" required class="w-full border-b-2 border-amber-100 p-2 outline-none focus:border-amber-600 font-bold uppercase">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">Jumlah Stok</label>
                    <input type="number" name="stok_pusat" required class="w-full border-b-2 border-amber-100 p-2 outline-none focus:border-amber-600 font-bold text-2xl">
                </div>
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">Satuan Besar</label>
                    <select name="satuan_besar" class="w-full border-b-2 border-amber-100 p-2 outline-none focus:border-amber-600 font-bold uppercase">
                        <option value="KARUNG">KARUNG (50KG)</option>
                        <option value="TON">TON</option>
                        <option value="BAL">BAL / DUS</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-amber-600 uppercase">Harga Per Satuan (Rp)</label>
                <input type="number" name="harga_per_satuan" required class="w-full border-b-2 border-amber-100 p-2 outline-none focus:border-amber-600 font-bold">
            </div>
            <button type="submit" class="w-full bg-amber-600 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-amber-700 transition-all">Simpan ke Gudang Pusat</button>
            <button type="button" onclick="toggleModal('modalBarangMasuk')" class="w-full text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-2">Batal</button>
        </form>
    </div>
</div>

{{-- MODAL 2: BUAT FAKTUR (LUAR DESA) --}}
<div id="modalFaktur" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-amber-100">
        <div class="bg-amber-600 p-6 text-white text-center font-black uppercase tracking-widest"><i class="fas fa-truck mr-2"></i> Pengiriman Luar Desa</div>
        <form id="formFaktur" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="text-center mb-4">
                <h4 id="faktur_nama" class="text-xl font-black text-slate-800 uppercase tracking-tighter"></h4>
                <p id="faktur_stok_info" class="text-[10px] text-amber-600 font-black uppercase"></p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">Tujuan Desa</label>
                    <input type="text" name="tujuan" required class="w-full border-b-2 border-amber-100 p-2 font-bold outline-none focus:border-amber-600 uppercase">
                </div>
                <div>
                    <label class="text-[10px] font-black text-amber-600 uppercase">No Pol Truk</label>
                    <input type="text" name="nopol" required class="w-full border-b-2 border-amber-100 p-2 font-bold outline-none focus:border-amber-600 uppercase">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-amber-600 uppercase block text-center mb-2">Jumlah Kirim</label>
                <input type="number" name="qty_faktur" id="faktur_qty_input" required class="w-full border-b-4 border-amber-100 p-4 text-center text-4xl font-black outline-none focus:border-amber-600">
            </div>
            <button type="submit" class="w-full bg-amber-600 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-amber-700 transition-all">Cetak Faktur & Kirim</button>
            <button type="button" onclick="toggleModal('modalFaktur')" class="w-full text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-2">Batal</button>
        </form>
    </div>
</div>

{{-- MODAL 3: MUTASI GERAI (INTERNAL) --}}
<div id="modalMutasi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        <div class="bg-slate-700 p-6 text-white text-center font-black uppercase tracking-widest"><i class="fas fa-exchange-alt mr-2"></i> Mutasi Internal Gerai</div>
        <form id="formMutasi" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="text-center">
                <h4 id="mutasi_nama" class="text-xl font-black text-slate-800 uppercase tracking-tighter"></h4>
                <p id="mutasi_stok_info" class="text-[10px] text-slate-400 font-bold uppercase"></p>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase block text-center mb-2">Jumlah Mutasi</label>
                <input type="number" name="qty_mutasi" id="mutasi_qty_input" required class="w-full border-b-4 border-slate-200 p-4 text-center text-4xl font-black outline-none focus:border-slate-700">
            </div>
            <button type="submit" class="w-full bg-slate-700 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-slate-800 transition-all">Konfirmasi Mutasi</button>
            <button type="button" onclick="toggleModal('modalMutasi')" class="w-full text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-2">Batal</button>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openFakturModal(barang) {
        document.getElementById('modalFaktur').classList.remove('hidden');
        document.getElementById('faktur_nama').innerText = barang.nama_barang;
        document.getElementById('faktur_stok_info').innerText = 'Stok Tersedia: ' + barang.stok_pusat + ' ' + barang.satuan_besar;
        document.getElementById('faktur_qty_input').max = barang.stok_pusat;
        document.getElementById('formFaktur').action = `/gudang-distribusi/kirim-luar/${barang.id}`;
    }

    function openMutasiModal(barang) {
        document.getElementById('modalMutasi').classList.remove('hidden');
        document.getElementById('mutasi_nama').innerText = barang.nama_barang;
        document.getElementById('mutasi_stok_info').innerText = 'Stok Tersedia: ' + barang.stok_pusat + ' ' + barang.satuan_besar;
        document.getElementById('mutasi_qty_input').max = barang.stok_pusat;
        document.getElementById('formMutasi').action = `/gudang-distribusi/mutasi-gerai/${barang.id}`;
    }
</script>
@endsection