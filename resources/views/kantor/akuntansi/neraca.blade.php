@extends('layouts.master')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
        .bg-slate-50 { background-color: white !important; }
        .shadow-2xl { box-shadow: none !important; }
    }
</style>

<div class="print-area p-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white border-2 border-slate-800 p-12 shadow-2xl relative">
        <div class="text-center mb-10">
            <h1 class="text-2xl font-black uppercase tracking-widest text-slate-800">Koperasi Merah Putih</h1>
            <h2 class="text-xl font-bold uppercase underline decoration-slate-400">Laporan Neraca (Balance Sheet)</h2>
            <p class="text-slate-500 font-bold uppercase text-[10px] mt-1">Per Tanggal: {{ date('d F Y') }}</p>
        </div>

        <div class="grid grid-cols-2 gap-12">
            <div class="border-r-2 border-slate-200 pr-6">
                <h3 class="bg-slate-800 text-white px-4 py-2 font-black uppercase text-[11px] mb-4 tracking-widest">I. AKTIVA (ASET)</h3>
                <div class="space-y-1">
                    @foreach($asets as $a)
                    <div class="flex justify-between py-2 border-b border-slate-50 font-bold text-slate-700 text-xs uppercase">
                        <span>{{ $a->nama_akun }}</span>
                        <span class="font-mono">Rp {{ number_format($a->saldo_awal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                {{-- Total Aktiva menggunakan sum saldo_awal --}}
                <div class="flex justify-between py-4 mt-10 border-t-4 border-slate-800 font-black text-base bg-slate-50 px-2 uppercase italic">
                    <span>TOTAL AKTIVA</span>
                    <span>Rp {{ number_format($asets->sum('saldo_awal'), 0, ',', '.') }}</span>
                </div>
            </div>

            <div>
                <h3 class="bg-slate-800 text-white px-4 py-2 font-black uppercase text-[11px] mb-4 tracking-widest">II. KEWAJIBAN (UTANG)</h3>
                <div class="space-y-1">
                    @foreach($liabilitas as $l)
                    <div class="flex justify-between py-2 border-b border-slate-50 font-bold text-slate-700 text-xs italic uppercase">
                        <span>{{ $l->nama_akun }}</span>
                        <span class="font-mono text-rose-700">Rp {{ number_format($l->saldo_awal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <h3 class="bg-slate-600 text-white px-4 py-2 font-black uppercase text-[11px] mt-8 mb-4 tracking-widest">III. EKUITAS (MODAL)</h3>
                <div class="space-y-1">
                    @foreach($ekuitas as $e)
                    <div class="flex justify-between py-2 border-b border-slate-50 font-bold text-slate-700 text-xs uppercase">
                        <span>{{ $e->nama_akun }}</span>
                        <span class="font-mono">Rp {{ number_format($e->saldo_awal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    
                    {{-- SHU Otomatis masuk ke Pasiva --}}
                    <div class="flex justify-between py-2 font-black text-emerald-700 text-xs uppercase italic">
                        <span>SHU TAHUN BERJALAN</span>
                        <span class="font-mono">Rp {{ number_format($shuTahunBerjalan, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Total Pasiva (Liabilitas + Ekuitas + SHU) --}}
                <div class="flex justify-between py-4 mt-10 border-t-4 border-slate-800 font-black text-base bg-slate-50 px-2 uppercase italic">
                    <span>TOTAL PASIVA</span>
                    <span>Rp {{ number_format($liabilitas->sum('saldo_awal') + $ekuitas->sum('saldo_awal') + $shuTahunBerjalan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="mt-16 grid grid-cols-2 text-center text-[10px] font-bold uppercase tracking-widest">
            <div>
                <p class="mb-20">Ketua Koperasi</p>
                <p class="underline">H. Sujatno, M.M.</p>
            </div>
            <div>
                <p class="mb-20">Bendahara</p>
                <p class="underline">{{ auth()->user()->nama_lengkap ?? 'Petugas Akuntansi' }}</p>
            </div>
        </div>

        <div class="mt-12 text-center no-print">
            <button onclick="window.print()" class="bg-slate-800 hover:bg-slate-900 text-white px-10 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-xl transition-all">
                <i class="fas fa-print mr-2"></i> Cetak Neraca
            </button>
        </div>
    </div>
</div>
@endsection