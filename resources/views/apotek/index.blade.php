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
                <a href="{{ route('apotek.gudang') }}" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fas fa-warehouse mr-2"></i> Cek Stok Gudang
                </a>
                <a href="{{ route('apotek.resep') }}" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fa-solid fa-book mr-2"></i> Orderan Masuk
                </a>
            </div>
        </div>

        {{-- Container Tabel Hijau --}}
        <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
            <div class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-pills mr-2"></i> Daftar Obat Siap Jual</span>
                <span class="bg-white text-emerald-600 px-4 py-1 rounded-full text-xs shadow-inner font-black">
                    Tersedia: {{ $obats->count() }} Macam Obat
                </span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest">
                        <tr>
                            <th class="py-4">KODE</th>
                            <th class="py-4">NAMA OBAT</th>
                            <th class="py-4">HARGA JUAL</th>
                            <th class="py-4 text-center">STOK RETAIL</th>
                            <th class="py-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50 font-bold">
                        @forelse($obats as $o)
                        <tr class="hover:bg-emerald-50/50 transition-colors text-slate-700 group">
                            <td class="py-4 font-mono text-emerald-600">{{ $o->kode_obat }}</td>
                            <td class="py-4 uppercase tracking-tighter">{{ $o->nama_obat }}</td>
                            <td class="py-4 text-slate-800">Rp {{ number_format($o->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-4 text-center">
                                <span class="px-4 py-1 bg-emerald-100 text-emerald-700 rounded-xl text-xs border border-emerald-200 shadow-sm">
                                    {{ $o->stok_apotek }} {{ $o->satuan }}
                                </span>
                            </td>
                            <td class="py-4 text-center">
                                {{-- Tombol Aktif Jual Obat Membuka Modal --}}
                                <button onclick="openJualModal({{ json_encode($o) }})" class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase shadow-md hover:bg-emerald-700 transition-all hover:scale-105 active:scale-95">
                                    <i class="fas fa-shopping-cart mr-1"></i> Jual Obat
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                Stok di Apotek kosong.<br>
                                <span class="text-xs uppercase not-italic text-emerald-600 font-black">Silakan lakukan mutasi stok dari gudang apotek</span>
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
<div id="modalJualObat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-emerald-100 animate-in fade-in zoom-in duration-300">
        <div class="bg-emerald-600 p-6 text-white text-center">
            <h3 class="text-xl font-black uppercase tracking-widest"><i class="fas fa-cash-register mr-2"></i> Transaksi Jual</h3>
        </div>
        
        <form id="formJualObat" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="text-center space-y-1">
                <h2 id="displayNamaObat" class="text-2xl font-black text-slate-800 uppercase tracking-tighter"></h2>
                <p id="displayHargaObat" class="text-emerald-600 font-bold italic"></p>
            </div>

            <div class="bg-emerald-50 p-6 rounded-2xl border-2 border-emerald-100">
                <label class="text-[10px] font-black text-emerald-600 uppercase block mb-2 text-center">Jumlah Pembelian</label>
                <div class="flex items-center justify-center gap-4">
                    <input type="number" name="qty" id="inputQty" required min="1" 
                        class="w-full bg-transparent border-b-4 border-emerald-200 text-center text-4xl font-black text-slate-800 outline-none focus:border-emerald-600 transition-all" 
                        value="1" oninput="hitungTotal()">
                </div>
                <p id="displayStokTersedia" class="text-[10px] text-center mt-3 text-slate-400 font-bold uppercase"></p>
            </div>

            <div class="border-t border-dashed border-emerald-200 pt-4 flex justify-between items-center">
                <span class="text-xs font-black text-slate-400 uppercase">Total Bayar</span>
                <span id="displayTotal" class="text-2xl font-black text-emerald-600">Rp 0</span>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-emerald-700 transition-all active:scale-95">
                    Konfirmasi Penjualan
                </button>
                <button type="button" onclick="closeModal()" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition-all">
                    Batal / Kembali
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let currentObat = null;

    function openJualModal(obat) {
        currentObat = obat;
        document.getElementById('modalJualObat').classList.remove('hidden');
        document.getElementById('displayNamaObat').innerText = obat.nama_obat;
        document.getElementById('displayHargaObat').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(obat.harga_jual) + ' / ' + obat.satuan;
        document.getElementById('displayStokTersedia').innerText = 'Stok Tersedia: ' + obat.stok_apotek + ' ' + obat.satuan;
        document.getElementById('inputQty').max = obat.stok_apotek;
        document.getElementById('inputQty').value = 1;
        
        // Set Action Form Dinamis
        document.getElementById('formJualObat').action = `/apotek/proses-jual/${obat.id}`;
        
        hitungTotal();
    }

    function hitungTotal() {
        const qty = document.getElementById('inputQty').value;
        const total = qty * currentObat.harga_jual;
        document.getElementById('displayTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function closeModal() {
        document.getElementById('modalJualObat').classList.add('hidden');
    }
</script>
@endsection