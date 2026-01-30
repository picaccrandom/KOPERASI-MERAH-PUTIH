@extends('layouts.master')

@section('content')
<div class="p-8 bg-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8 border-b-2 border-slate-300 pb-5">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tighter uppercase">Manajemen <span class="text-red-700">Keuangan</span></h1>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-1">Pencatatan Arus Kas Keluar (Cash Out)</p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-200">
                <span class="text-slate-400 text-[10px] font-black uppercase block">Status Kas Saat Ini</span>
                <span class="text-2xl font-black text-slate-800 font-mono tracking-tighter italic">Connected</span>
            </div>
        </div>

        {{-- NOTIFIKASI SUKSES / ERROR --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500 text-white font-black rounded-2xl shadow-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-600 text-white font-black rounded-2xl shadow-lg">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-100 border-l-4 border-rose-500 text-rose-800 font-bold rounded-r-2xl shadow-md">
                <ul class="text-xs">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- FORM INPUT --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-800 p-6 text-white uppercase font-black tracking-widest text-xs italic">
                        Input Pengeluaran Baru
                    </div>
                    <form action="{{ route('keuangan.store') }}" method="POST" class="p-8 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kategori Biaya (Beban)</label>
                            <select name="account_id" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-700 focus:border-slate-800 outline-none transition-all" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->kode_akun }} - {{ $acc->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nominal (Rp)</label>
                            <input type="number" name="nominal" placeholder="Contoh: 500000" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-mono font-black text-slate-800 focus:border-slate-800 outline-none transition-all" required>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Keterangan / Deskripsi</label>
                            <textarea name="keterangan" rows="3" placeholder="Contoh: Bayar Listrik Kantor" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 font-bold text-slate-700 focus:border-slate-800 outline-none transition-all uppercase text-xs" required></textarea>
                        </div>

                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl transition-all uppercase tracking-widest text-sm">
                            <i class="fas fa-save mr-2"></i> Posting Jurnal
                        </button>
                    </form>
                </div>
            </div>

            {{-- TABEL LOG --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
                    <div class="bg-slate-100 px-8 py-5 border-b border-slate-200 flex justify-between items-center">
                        <span class="text-slate-800 font-black uppercase tracking-widest text-xs italic">Log Transaksi Terakhir</span>
                    </div>
                    <div class="overflow-x-auto p-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                                    <th class="p-4">Tanggal</th>
                                    <th class="p-4">Kategori</th>
                                    <th class="p-4">Keterangan</th>
                                    <th class="p-4 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 uppercase">
                                @forelse($pengeluarans as $p)
                                <tr class="hover:bg-slate-50 transition-colors font-bold text-slate-700 text-xs">
                                    <td class="p-4">{{ date('d/m/Y', strtotime($p->tgl_transaksi)) }}</td>
                                    <td class="p-4">
                                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[9px] border border-slate-200">
                                            {{ $p->account->nama_akun ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-4 italic text-slate-500">{{ $p->keterangan }}</td>
                                    <td class="p-4 text-right text-rose-600 font-black font-mono">
                                        Rp {{ number_format($p->debit + $p->kredit, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-slate-300 uppercase font-black italic tracking-widest">Belum ada catatan biaya</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection