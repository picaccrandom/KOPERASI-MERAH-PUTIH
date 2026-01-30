@extends('layouts.master')

@section('content')
<div class="p-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-end mb-10 border-l-8 border-slate-800 pl-6">
            <div>
                <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-800">
                    Kantor <span class="bg-slate-800 text-white px-3 rounded-lg shadow-lg">Akuntansi</span>
                </h1>
                <p class="text-slate-500 font-bold mt-2 italic text-sm">Chart of Accounts (Co-A) - Koperasi Merah Putih</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('akuntansi.labarugi') }}" class="bg-emerald-700 hover:bg-emerald-800 px-5 py-3 rounded-xl font-black text-white shadow-xl transition-all uppercase text-[10px] tracking-widest flex items-center italic">Laba Rugi</a>
                <a href="{{ route('akuntansi.neraca') }}" class="bg-blue-700 hover:bg-blue-800 px-5 py-3 rounded-xl font-black text-white shadow-xl transition-all uppercase text-[10px] tracking-widest flex items-center italic">Neraca</a>
                <button onclick="openModalAccount()" class="bg-slate-800 hover:bg-slate-900 px-5 py-3 rounded-xl font-black text-white shadow-xl transition-all uppercase text-[10px] tracking-widest flex items-center italic text-xs">
                    <i class="fas fa-plus-circle mr-2 text-base"></i> Tambah Akun
                </button>
            </div>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500 text-white font-black rounded-xl shadow-lg animate-bounce">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-200">
            <div class="bg-slate-800 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center italic text-xs">
                <span><i class="fas fa-list-ul mr-2"></i> Struktur Kode Akun (Real-Time)</span>
                <span class="bg-white/20 px-4 py-1 rounded-full text-[10px] font-black uppercase">Standard Akuntansi Koperasi</span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-slate-500 border-b-2 border-slate-100 font-black uppercase text-xs tracking-widest italic">
                        <tr>
                            <th class="py-4">KODE AKUN</th>
                            <th class="py-4">NAMA AKUN</th>
                            <th class="py-4">KATEGORI</th>
                            <th class="py-4 text-right">SALDO SAAT INI</th>
                            <th class="py-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-bold text-slate-700 uppercase">
                        @foreach($accounts as $acc)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="py-4 font-mono text-blue-600 tracking-tighter">{{ $acc->kode_akun }}</td>
                            <td class="py-4 tracking-tighter">{{ $acc->nama_akun }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[9px] font-black uppercase border border-slate-200">
                                    {{ $acc->kategori }}
                                </span>
                            </td>
                            <td class="py-4 text-right font-mono text-emerald-600">
                                @php
                                    // Logika Saldo Normal Akuntansi
                                    if(in_array($acc->kategori, ['Aset', 'Beban'])) {
                                        $saldoSekarang = $acc->saldo_awal + ($acc->jurnals->sum('debit') - $acc->jurnals->sum('kredit'));
                                    } else {
                                        $saldoSekarang = $acc->saldo_awal + ($acc->jurnals->sum('kredit') - $acc->jurnals->sum('debit'));
                                    }
                                @endphp
                                Rp {{ number_format($saldoSekarang, 0, ',', '.') }}
                            </td>
                            <td class="py-4 text-center">
                                <a href="{{ route('akuntansi.ledger', $acc->id) }}" class="bg-slate-100 group-hover:bg-slate-800 group-hover:text-white text-slate-800 px-4 py-2 rounded-xl text-[10px] font-black uppercase shadow-sm transition-all inline-block italic">
                                    <i class="fas fa-book-open mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH AKUN --}}
<div id="modalAccount" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border-2 border-slate-800 animate-in fade-in zoom-in duration-300">
        <div class="bg-slate-800 p-6 flex justify-between items-center text-white italic">
            <h3 class="font-black uppercase tracking-widest text-xs">Tambah Co-A Baru</h3>
            <button onclick="closeModalAccount()" class="text-white/50 hover:text-white text-2xl font-black">&times;</button>
        </div>
        
        <form action="{{ route('akuntansi.store_account') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kode Akun</label>
                <input type="text" name="kode_akun" placeholder="Contoh: 1101" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-mono font-black text-slate-800 focus:border-slate-800 outline-none transition-all uppercase" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nama Akun</label>
                <input type="text" name="nama_akun" placeholder="Contoh: Kas Utama" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-700 focus:border-slate-800 outline-none transition-all uppercase" required>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kategori</label>
                <select name="kategori" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-black text-slate-700 focus:border-slate-800 outline-none transition-all cursor-pointer" required>
                    <option value="Aset">Aset (Harta)</option>
                    <option value="Liabilitas">Liabilitas (Utang)</option>
                    <option value="Ekuitas">Ekuitas (Modal)</option>
                    <option value="Pendapatan">Pendapatan</option>
                    <option value="Beban">Beban (Biaya)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Saldo Awal</label>
                <input type="number" name="saldo_awal" value="0" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-700 focus:border-slate-800 outline-none transition-all" required>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-sm italic">
                    <i class="fas fa-save mr-2"></i> Simpan Ke Database
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModalAccount() {
        document.getElementById('modalAccount').classList.remove('hidden');
        document.getElementById('modalAccount').classList.add('flex');
    }
    function closeModalAccount() {
        document.getElementById('modalAccount').classList.add('hidden');
        document.getElementById('modalAccount').classList.remove('flex');
    }
</script>
@endsection