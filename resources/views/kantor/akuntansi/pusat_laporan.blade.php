@extends('layouts.master')

@section('content')
<div class="p-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-black uppercase italic text-emerald-700">Pusat <span class="text-slate-800">Laporan Terpadu</span></h1>
            <a href="{{ route('akuntansi.index') }}" class="bg-slate-800 text-white px-6 py-2 rounded-lg font-bold uppercase italic shadow-lg">Kembali</a>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-xl mb-10 border border-slate-100">
            <form action="{{ route('kantor.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Pilih Jenis Laporan</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="pendapatan" {{ $type == 'pendapatan' ? 'selected' : '' }}>Laporan Pendapatan</option>
                        <option value="penjualan" {{ $type == 'penjualan' ? 'selected' : '' }}>Laporan Penjualan Kasir</option>
                        <option value="anggota" {{ $type == 'anggota' ? 'selected' : '' }}>Laporan Data Anggota</option>
                    </select>
                </div>
                @if($type != 'anggota')
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ $tgl_mulai ?? date('Y-m-01') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ $tgl_selesai ?? date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700">
                </div>
                @endif
                <div class="{{ $type == 'anggota' ? 'md:col-span-3' : '' }}">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-xl shadow-lg transition-all uppercase italic">
                        <i class="fas fa-search mr-2"></i> Lihat Laporan
                    </button>
                </div>
            </form>
        </div>

        @if($type && !$data->isEmpty())
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
            <div class="bg-slate-800 px-8 py-6 flex justify-between items-center text-white">
                <div>
                    <h2 class="text-2xl font-black uppercase italic tracking-tighter">{{ $title }}</h2>
                    <p class="text-emerald-400 text-[10px] font-bold uppercase tracking-widest">
                        @if($type != 'anggota')
                            Periode: {{ date('d M Y', strtotime($tgl_mulai)) }} - {{ date('d M Y', strtotime($tgl_selesai)) }}
                        @else
                            Total Anggota Aktif: {{ $data->count() }} Orang
                        @endif
                    </p>
                </div>
                <button onclick="window.print()" class="bg-white text-slate-800 px-6 py-2 rounded-lg font-black text-xs uppercase italic shadow-lg">
                    <i class="fas fa-print mr-2"></i> Print Laporan
                </button>
            </div>

            <div class="p-8 overflow-x-auto">
                <table class="w-full text-left">
                    
                    {{-- HEADER TABEL UNTUK ANGGOTA --}}
                    @if($type == 'anggota')
                    <thead class="text-slate-400 border-b-2 border-slate-100 text-[11px] font-black uppercase italic">
                        <tr>
                            <th class="py-4">No. Anggota</th>
                            <th class="py-4">NIK</th>
                            <th class="py-4">Nama Lengkap</th>
                            <th class="py-4">No. HP</th>
                            <th class="py-4">Alamat</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold text-sm uppercase">
                        @foreach($data as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-all">
                            <td class="py-4 text-emerald-600">MBR-{{ str_pad($row->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 font-mono">{{ $row->nik }}</td>
                            <td class="py-4">{{ $row->nama_lengkap }}</td>
                            <td class="py-4">{{ $row->nomor_hp }}</td>
                            <td class="py-4 text-xs">{{ $row->alamat }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                    {{-- HEADER TABEL UNTUK PENDAPATAN / PENJUALAN --}}
                    @else
                    <thead class="text-slate-400 border-b-2 border-slate-100 text-[11px] font-black uppercase italic">
                        <tr>
                            <th class="py-4">Tanggal</th>
                            <th class="py-4">Keterangan / Referensi</th>
                            <th class="py-4 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold text-sm uppercase">
                        @php $total = 0; @endphp
                        @foreach($data as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50">
                            <td class="py-4">{{ date('d/m/Y', strtotime($row->tgl_transaksi ?? $row->created_at)) }}</td>
                            <td class="py-4 italic">{{ $row->keterangan ?? ($row->referensi ?? 'Transaksi Retail') }}</td>
                            <td class="py-4 text-right">
                                @php 
                                    $val = $row->kredit ?? ($row->total_harga ?? 0);
                                    $total += $val;
                                @endphp
                                Rp {{ number_format($val, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 text-slate-900 font-black">
                            <td colspan="2" class="py-5 text-center uppercase tracking-widest text-xs">Total Akumulasi</td>
                            <td class="py-5 text-right text-emerald-700 text-lg">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif

                </table>
            </div>
        </div>
        @elseif($type)
            <div class="bg-white p-20 rounded-3xl shadow-xl text-center border border-slate-100">
                <i class="fas fa-folder-open text-6xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 font-black italic uppercase italic">Data tidak ditemukan untuk parameter tersebut.</p>
            </div>
        @endif
    </div>
</div>
@endsection