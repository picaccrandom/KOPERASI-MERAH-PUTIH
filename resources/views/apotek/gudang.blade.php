@extends('layouts.master')

@section('content')
{{-- Background menggunakan asset apotek dengan overlay Hijau Emerald --}}
<div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-apotek.png') }}');">
    <div class="bg-emerald-900/40 backdrop-blur-md min-h-screen p-10">
        
        {{-- Header Modul - Tema Hijau --}}
        <div class="flex justify-between items-end mb-10 border-l-8 border-emerald-500 pl-6 text-white">
            <div>
                <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-100">
                    Gudang <span class="bg-emerald-600 px-3 rounded-lg shadow-lg">Apotek</span>
                </h1>
                <p class="font-bold mt-2 italic text-emerald-100">Manajemen Stok Obat Desa Nangsri</p>
            </div>
            {{-- Tombol Trigger Modal Tambah Obat --}}
            <button onclick="toggleModal('modalTambahObat')" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest flex items-center text-white">
                <i class="fas fa-pills mr-2 text-xl"></i> Tambah Obat Baru
            </button>
        </div>

        {{-- Tabel Data Obat Hijau --}}
        <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
            <div class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-warehouse mr-2"></i> Inventaris Farmasi Terpusat</span>
                <span class="bg-white/20 px-4 py-1 rounded-full text-xs shadow-inner">Update: {{ now()->format('H:i') }} WIB</span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest">
                        <tr>
                            <th class="py-4 text-center">KODE</th>
                            <th class="py-4">NAMA OBAT</th>
                            <th class="py-4 text-center">STOK GUDANG</th>
                            <th class="py-4 text-center">STOK APOTEK</th>
                            <th class="py-4 text-center">AKSI MUTASI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @forelse($obats as $o)
                        <tr class="hover:bg-emerald-50/50 transition-colors font-bold text-slate-700 group">
                            <td class="py-4 text-center font-mono text-emerald-600">{{ $o->kode_obat }}</td>
                            <td class="py-4 uppercase tracking-tighter">{{ $o->nama_obat }}</td>
                            <td class="py-4 text-center bg-emerald-50/30 font-black text-emerald-700">
                                {{ $o->stok_gudang }} <span class="text-[10px] text-slate-400 font-medium">{{ $o->satuan }}</span>
                            </td>
                            <td class="py-4 text-center font-black text-slate-800">
                                {{ $o->stok_apotek }} <span class="text-[10px] text-slate-400 font-medium">{{ $o->satuan }}</span>
                            </td>
                            <td class="py-4 text-center">
                                {{-- Form Mutasi Stok --}}
                                <form action="{{ route('apotek.kirim', $o->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="number" name="jumlah" required min="1" max="{{ $o->stok_gudang }}" 
                                        class="w-20 p-2 border border-emerald-200 rounded-xl text-center shadow-inner text-sm focus:ring-2 focus:ring-emerald-500 outline-none" 
                                        placeholder="Qty">
                                    <button type="submit" class="bg-emerald-600 text-white p-2 px-3 rounded-xl hover:bg-emerald-700 shadow-lg transition-all active:scale-95 text-[10px] font-black uppercase">
                                        <i class="fas fa-shipping-fast mr-1"></i> Kirim
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                Belum ada data obat di gudang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH OBAT BARU --}}
<div id="modalTambahObat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
        <div class="bg-emerald-600 p-6 text-white flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-widest"><i class="fas fa-pills mr-2"></i> Input Obat Baru</h3>
            <button onclick="toggleModal('modalTambahObat')" class="text-white/70 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('apotek.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-emerald-600 uppercase">Kode Obat</label>
                    <input type="text" name="kode_obat" required class="w-full border-b-2 border-emerald-100 p-2 outline-none focus:border-emerald-600 font-bold uppercase" placeholder="OBT-XXXX">
                </div>
                <div>
                    <label class="text-[10px] font-black text-emerald-600 uppercase">Nama Obat</label>
                    <input type="text" name="nama_obat" required class="w-full border-b-2 border-emerald-100 p-2 outline-none focus:border-emerald-600 font-bold uppercase" placeholder="PARACETAMOL">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-emerald-600 uppercase">Stok Gudang Awal</label>
                    <input type="number" name="stok_gudang" required class="w-full border-b-2 border-emerald-100 p-2 outline-none focus:border-emerald-600 font-bold" value="0">
                </div>
                <div>
                    <label class="text-[10px] font-black text-emerald-600 uppercase">Satuan</label>
                    <select name="satuan" class="w-full border-b-2 border-emerald-100 p-2 outline-none focus:border-emerald-600 font-bold">
                        <option value="TABLET">TABLET</option>
                        <option value="BOTOL">BOTOL</option>
                        <option value="STRIP">STRIP</option>
                        <option value="PCS">PCS</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-emerald-600 uppercase">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" required class="w-full border-b-2 border-emerald-100 p-2 outline-none focus:border-emerald-600 font-bold" placeholder="0">
            </div>
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-emerald-600 text-white px-10 py-3 rounded-xl font-black uppercase shadow-lg hover:bg-emerald-700 transition-all">
                    Simpan Obat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }
</script>
@endsection