@extends('layouts.master')

@section('content')
<style>
    /* CSS khusus saat diprint */
    @media print {
        .no-print { display: none !important; }
        .print-area { 
            border: none !important; 
            box-shadow: none !important; 
            margin: 0 !important; 
            padding: 0 !important;
            width: 100% !important;
        }
        body { background: white !important; }
    }
</style>

<div class="p-10 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        
        {{-- Tombol Navigasi & Cetak --}}
        <div class="flex justify-between items-center mb-6 no-print">
            <a href="{{ route('akuntansi.index') }}" class="text-slate-600 font-bold hover:text-slate-900">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Akuntansi
            </a>
            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-black uppercase text-xs tracking-widest shadow-xl hover:bg-slate-900 transition-all">
                <i class="fas fa-print mr-2 text-lg"></i> Cetak Laporan
            </button>
        </div>

        {{-- AREA LAPORAN --}}
        <div class="print-area bg-white border-4 border-slate-800 p-12 shadow-2xl relative overflow-hidden">
            {{-- Watermark Latar Belakang --}}
            <div class="absolute -top-10 -right-10 text-slate-50 opacity-10 no-print">
                <i class="fas fa-chart-line text-[200px]"></i>
            </div>

            <div class="text-center mb-10 relative">
                <h1 class="text-3xl font-black uppercase text-slate-800 tracking-tighter">Koperasi Merah Putih</h1>
                <p class="font-bold text-slate-500 uppercase tracking-widest text-xs">Unit Kantor Pusat - Modul Akuntansi</p>
                <p class="text-slate-400 font-medium italic mt-1 text-[10px] tracking-tight">Alamat: Jl. Raya Koperasi No. 01, Jakarta</p>
                <div class="h-1 bg-slate-800 w-24 mx-auto mt-6"></div>
                <h2 class="text-xl font-black mt-4 uppercase underline decoration-double decoration-slate-300">Laporan Laba Rugi</h2>
                <p class="text-slate-600 font-bold mt-1 uppercase text-[10px]">Periode: {{ date('01 F Y') }} - {{ date('d F Y') }}</p>
            </div>

            {{-- SEKSI I: PENDAPATAN --}}
            <div class="mb-10 relative">
                <h3 class="text-sm font-black border-b-2 border-slate-200 pb-2 mb-4 uppercase text-emerald-800 flex justify-between">
                    <span>I. Pendapatan Operasional</span>
                    <span class="text-[9px] font-normal text-slate-400 italic">Data Real-time</span>
                </h3>
                <div class="space-y-3">
                    @foreach($pendapatans as $p)
                    <div class="flex justify-between font-bold text-slate-700 uppercase text-xs">
                        <span>{{ $p->nama_akun }}</span>
                        {{-- Menggunakan saldo_awal sesuai database Mas Yoga --}}
                        <span class="font-mono tracking-tighter">Rp {{ number_format($p->saldo_awal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-6 pt-4 border-t border-dashed border-slate-300 font-black text-base text-slate-900 uppercase italic">
                    <span>Subtotal Pendapatan</span>
                    <span>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- SEKSI II: BEBAN --}}
            <div class="mb-12 relative">
                <h3 class="text-sm font-black border-b-2 border-slate-200 pb-2 mb-4 uppercase text-rose-800 flex justify-between">
                    <span>II. Beban & Biaya Kantor</span>
                    <span class="text-[9px] font-normal text-slate-400 italic">Pengeluaran Kas</span>
                </h3>
                <div class="space-y-3">
                    @foreach($bebans as $b)
                    <div class="flex justify-between font-bold text-slate-700 uppercase text-xs">
                        <span>{{ $b->nama_akun }}</span>
                        {{-- Menggunakan saldo_awal sesuai database Mas Yoga --}}
                        <span class="font-mono tracking-tighter text-rose-700 font-black">(Rp {{ number_format($b->saldo_awal, 0, ',', '.') }})</span>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-6 pt-4 border-t border-dashed border-slate-300 font-black text-base text-slate-900 uppercase italic">
                    <span>Subtotal Beban</span>
                    <span>(Rp {{ number_format($totalBeban, 0, ',', '.') }})</span>
                </div>
            </div>

            {{-- RINGKASAN AKHIR (SHU) --}}
            <div class="bg-slate-800 text-white p-8 rounded-2xl flex justify-between items-center shadow-inner relative z-10 border-b-4 border-slate-600">
                <div class="uppercase tracking-widest font-black text-lg leading-tight">
                    Sisa Hasil Usaha <br> <span class="text-slate-400 text-[10px] italic font-bold">(Net Profit / Loss)</span>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-mono font-black italic">Rp {{ number_format($shu, 0, ',', '.') }}</span>
                    @if($shu > 0)
                        <span class="block text-[9px] uppercase font-black text-emerald-400 tracking-widest mt-1">STATUS: SURPLUS / UNTUNG</span>
                    @else
                        <span class="block text-[9px] uppercase font-black text-rose-400 tracking-widest mt-1">STATUS: DEFISIT / RUGI</span>
                    @endif
                </div>
            </div>

            {{-- Bagian Tanda Tangan --}}
            <div class="mt-20 flex justify-between text-center px-10">
                <div class="w-48">
                    <p class="text-[10px] font-bold uppercase mb-16 text-slate-500 italic">Mengetahui, <br> Ketua Koperasi</p>
                    <div class="border-b-2 border-slate-800 w-full mx-auto mb-1"></div>
                    <p class="text-[10px] font-black uppercase tracking-tight">H. Sujatno, M.M.</p>
                </div>
                <div class="w-48">
                    <p class="text-[10px] font-bold uppercase mb-16 text-slate-500 italic">Dibuat Oleh, <br> Bendahara / Akuntan</p>
                    <div class="border-b-2 border-slate-800 w-full mx-auto mb-1"></div>
                    <p class="text-[10px] font-black uppercase tracking-widest">{{ auth()->user()->nama_lengkap ?? 'Petugas Akuntansi' }}</p>
                </div>
            </div>
        </div>

        <p class="text-center text-slate-400 text-[10px] mt-8 uppercase font-bold tracking-widest no-print italic opacity-60">
            Laporan ini digenerate secara otomatis oleh Sistem Koperasi Merah Putih v1.0
        </p>
    </div>
</div>
@endsection