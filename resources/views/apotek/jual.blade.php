@extends('layouts.master')
@section('content')
<div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-apotek.png') }}');">
    <div class="bg-emerald-900/40 backdrop-blur-md min-h-screen p-10">
        <div class="max-w-xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
            <div class="bg-emerald-600 p-6 text-white font-black uppercase tracking-widest text-center">
                <i class="fas fa-cash-register mr-2"></i> Transaksi Penjualan
            </div>
            <form action="{{ route('apotek.prosesJual', $obat->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="text-center">
                    <h2 class="text-2xl font-black text-slate-800 uppercase">{{ $obat->nama_obat }}</h2>
                    <p class="text-emerald-600 font-bold">Harga: Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="text-xs font-black text-emerald-600 uppercase">Jumlah Beli (Stok: {{ $obat->stok_apotek }})</label>
                    <input type="number" name="qty" required min="1" max="{{ $obat->stok_apotek }}" 
                        class="w-full border-b-4 border-emerald-100 p-4 outline-none focus:border-emerald-600 text-3xl font-black text-center" value="1">
                </div>
                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-emerald-700 transition-all">
                    Konfirmasi Penjualan
                </button>
                <a href="{{ route('apotek.index') }}" class="block text-center text-slate-400 font-bold uppercase text-xs">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection