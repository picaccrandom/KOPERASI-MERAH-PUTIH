@extends('layouts.master')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER LAPORAN --}}
        <div class="flex justify-between items-center mb-8 no-print">
            <div class="border-l-8 border-red-600 pl-6">
                <h1 class="text-4xl font-black uppercase tracking-tighter text-slate-800">
                    Pusat <span class="text-red-600">Laporan Terpadu</span>
                </h1>
                <p class="text-slate-500 font-bold italic">Monitoring Unit Gerai, Apotek, Klinik & Akuntansi</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-700 transition-all uppercase italic text-xs">
                    <i class="fas fa-print mr-2"></i> Print Laporan
                </button>
                <a href="{{ route('akuntansi.index') }}" class="bg-white border-2 border-slate-800 text-slate-800 px-6 py-3 rounded-xl font-bold uppercase italic text-xs shadow-sm hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </div>

        {{-- FILTER PANEL (Disesuaikan dengan field yang ada di Controller Akuntansi Mas) --}}
        <div class="bg-white p-6 rounded-3xl shadow-xl mb-8 border border-slate-100 no-print">
            <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Jenis Laporan</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-red-500">
                        <option value="omzet" {{ request('type') == 'omzet' ? 'selected' : '' }}>💰 Laporan Omzet (Semua Unit)</option>
                        <option value="simpanan" {{ request('type') == 'simpanan' ? 'selected' : '' }}>🏦 Laporan Simpanan Anggota</option>
                        <option value="pinjaman" {{ request('type') == 'pinjaman' ? 'selected' : '' }}>💳 Laporan Pinjaman & Angsuran</option>
                        <option value="anggota" {{ request('type') == 'anggota' ? 'selected' : '' }}>👥 Laporan Data Anggota</option>
                        <option value="stok" {{ request('type') == 'stok' ? 'selected' : '' }}>📦 Stok Barang Kritis</option>
                        <option value="piutang" {{ request('type') == 'piutang' ? 'selected' : '' }}>⚠️ Piutang / BON Anggota</option>
                        <option value="klinik" {{ request('type') == 'klinik' ? 'selected' : '' }}>🏥 Kunjungan Klinik (EMR)</option>
                        <option value="labarugi" {{ request('type') == 'labarugi' ? 'selected' : '' }}>📈 Laporan Laba Rugi</option>
                        <option value="neraca" {{ request('type') == 'neraca' ? 'selected' : '' }}>📊 Neraca / Balance Sheet</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') ?? date('Y-m-01') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') ?? date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700">
                </div>
                <div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-xl shadow-lg transition-all uppercase italic">
                        <i class="fas fa-filter mr-2"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>

        {{-- AREA TABEL LAPORAN --}}
        @if(isset($data) && !$data->isEmpty())
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 print:shadow-none print:border-none">
            
            {{-- Header Tabel Dinamis --}}
            <div class="bg-red-600 px-8 py-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black uppercase tracking-tighter">
                        @if(request('type') == 'stok') 
                            LAPORAN STOK KRITIS 
                        @elseif(request('type') == 'piutang') 
                            LAPORAN PIUTANG ANGGOTA 
                        @elseif(request('type') == 'klinik') 
                            LAPORAN KUNJUNGAN KLINIK 
                        @elseif(request('type') == 'simpanan')
                            LAPORAN SIMPANAN ANGGOTA
                        @elseif(request('type') == 'pinjaman')
                            LAPORAN PINJAMAN & ANGSURAN
                        @elseif(request('type') == 'anggota')
                            LAPORAN DATA ANGGOTA
                        @elseif(request('type') == 'labarugi')
                            LAPORAN LABA RUGI
                        @elseif(request('type') == 'neraca')
                            NERACA / BALANCE SHEET
                        @else 
                            LAPORAN TRANSAKSI TERPADU 
                        @endif
                    </h2>
                    <p class="text-red-100 text-[10px] font-bold uppercase tracking-widest">
                        Periode: {{ date('d M Y', strtotime(request('tgl_mulai', date('Y-m-01')))) }} - {{ date('d M Y', strtotime(request('tgl_selesai', date('Y-m-d')))) }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="bg-white/20 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">Dokumen Resmi</span>
                </div>
            </div>

            <div class="p-8 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-slate-400 border-b border-slate-100 text-[11px] font-black uppercase tracking-wider">
                        <tr>
                            {{-- KOLOM BERUBAH SESUAI TYPE --}}
                            @if(request('type') == 'stok')
                                <th class="py-4">Nama Barang/Obat</th>
                                <th class="py-4">Satuan</th>
                                <th class="py-4 text-center">Sisa Stok</th>
                                <th class="py-4">Kategori Unit</th>
                                <th class="py-4">Status</th>
                            @elseif(request('type') == 'simpanan')
                                <th class="py-4">Tanggal</th>
                                <th class="py-4">Nama Anggota</th>
                                <th class="py-4">Jenis Simpanan</th>
                                <th class="py-4 text-center">Nominal (Rp)</th>
                                <th class="py-4">Status</th>
                            @elseif(request('type') == 'pinjaman')
                                <th class="py-4">Tanggal</th>
                                <th class="py-4">Nama Anggota</th>
                                <th class="py-4">Tipe Transaksi</th>
                                <th class="py-4 text-right">Nominal (Rp)</th>
                                <th class="py-4">Keterangan</th>
                            @elseif(request('type') == 'anggota')
                                <th class="py-4">No. Anggota</th>
                                <th class="py-4">Nama Lengkap</th>
                                <th class="py-4">Email</th>
                                <th class="py-4">No. HP</th>
                                <th class="py-4">Status</th>
                            @elseif(request('type') == 'klinik')
                                <th class="py-4">Tanggal</th>
                                <th class="py-4">Nama Pasien</th>
                                <th class="py-4">Diagnosa Medis</th>
                                <th class="py-4 text-right">Biaya Tindakan</th>
                            @elseif(request('type') == 'piutang')
                                <th class="py-4">Tanggal</th>
                                <th class="py-4">Nama Anggota</th>
                                <th class="py-4">Kode Ref</th>
                                <th class="py-4 text-right">Total Transaksi</th>
                                <th class="py-4 text-right text-red-600">Total BON</th>
                            @elseif(request('type') == 'labarugi')
                                <th class="py-4 colspan=2>Laporan Laba Rugi</th>
                            @elseif(request('type') == 'neraca')
                                <th class="py-4">Akun</th>
                                <th class="py-4">Kategori</th>
                                <th class="py-4 text-right">Saldo (Rp)</th>
                            @else
                                <th class="py-4">Tanggal</th>
                                <th class="py-4">Kode Transaksi</th>
                                <th class="py-4">Nama Pelanggan</th>
                                <th class="py-4">Unit</th>
                                <th class="py-4 text-right">Nominal (Rp)</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold text-sm uppercase">
                        @php $totalRow = 0; $totalSec = 0; @endphp
                        @foreach($data as $row)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-all">
                            
                            @if(request('type') == 'stok')
                                <td class="py-4">{{ $row->nama_obat ?? $row->nama_barang }}</td>
                                <td class="py-4 text-slate-400">{{ $row->satuan ?? 'Pcs' }}</td>
                                <td class="py-4 text-center text-red-600 font-black">{{ $row->stok_apotek ?? $row->stok_tersedia }}</td>
                                <td class="py-4"><span class="px-2 py-1 bg-slate-100 rounded text-[9px] font-black">{{ $row->COA ?? 'Gudang' }}</span></td>
                                <td class="py-4"><span class="px-3 py-1 bg-red-100 text-red-600 text-[10px] rounded-full">BUTUH STOK</span></td>

                            @elseif(request('type') == 'simpanan')
                                <td class="py-4 text-xs text-slate-400">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                <td class="py-4">{{ $row->transaksi->member->nama_lengkap ?? 'N/A' }}</td>
                                <td class="py-4 text-sm">{{ strtoupper($row->jenis) }}</td>
                                <td class="py-4 text-right font-mono">Rp {{ number_format(abs($row->saldo), 0, ',', '.') }}</td>
                                <td class="py-4"><span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $row->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ strtoupper($row->status) }}</span></td>
                                @php $totalRow += abs($row->saldo); @endphp

                            @elseif(request('type') == 'pinjaman')
                                <td class="py-4 text-xs text-slate-400">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                <td class="py-4">{{ $row->member->nama_lengkap ?? 'N/A' }}</td>
                                <td class="py-4 text-sm"><span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $row->COA == 'Pinjaman' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">{{ strtoupper($row->COA) }}</span></td>
                                <td class="py-4 text-right font-mono">Rp {{ number_format($row->Nominal, 0, ',', '.') }}</td>
                                <td class="py-4 text-xs text-slate-400">{{ $row->Keterangan }}</td>
                                @php $totalRow += $row->Nominal; @endphp

                            @elseif(request('type') == 'anggota')
                                <td class="py-4 text-xs font-mono">{{ $row->id }}</td>
                                <td class="py-4">{{ $row->nama_lengkap }}</td>
                                <td class="py-4 text-xs">{{ $row->email }}</td>
                                <td class="py-4 text-xs">{{ $row->no_hp }}</td>
                                <td class="py-4"><span class="px-2 py-1 text-[10px] font-bold rounded-full {{ $row->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ strtoupper($row->status) }}</span></td>

                            @elseif(request('type') == 'klinik')
                                <td class="py-4 text-xs text-slate-400">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                <td class="py-4">{{ $row->member->nama_lengkap ?? 'Umum' }}</td>
                                <td class="py-4 italic text-xs text-slate-400">{{ $row->rekamMedis->diagnosa ?? 'Pemeriksaan' }}</td>
                                <td class="py-4 text-right">Rp {{ number_format($row->biaya_pendaftaran ?? $row->Nominal, 0, ',', '.') }}</td>
                                @php $totalRow += ($row->biaya_pendaftaran ?? $row->Nominal); @endphp

                            @elseif(request('type') == 'piutang')
                                <td class="py-4 text-xs text-slate-400">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                <td class="py-4">{{ $row->member->nama_lengkap ?? ($row->nama ?? 'Member') }}</td>
                                <td class="py-4 text-red-600 text-xs font-mono">{{ $row->kode_transaksi ?? $row->no_transaksi_sp }}</td>
                                <td class="py-4 text-right">Rp {{ number_format($row->total_harga ?? $row->Nominal, 0, ',', '.') }}</td>
                                <td class="py-4 text-right text-red-600 font-black">
                                    Rp {{ number_format($row->total_bon ?? $row->Nominal, 0, ',', '.') }}
                                </td>
                                @php $totalRow += ($row->total_harga ?? $row->Nominal); $totalSec += ($row->total_bon ?? $row->Nominal); @endphp

                            @elseif(request('type') == 'labarugi')
                                {{-- Laporan Laba Rugi --}}

                            @elseif(request('type') == 'neraca')
                                {{-- Neraca --}}

                            @else
                                <td class="py-4 text-xs text-slate-400">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
                                <td class="py-4 text-red-600 font-mono text-xs">{{ $row->kode_transaksi }}</td>
                                <td class="py-4">{{ $row->member->nama_lengkap ?? ($row->nama ?? 'Umum') }}</td>
                                <td class="py-4"><span class="px-2 py-1 bg-slate-100 rounded text-[9px] font-black">{{ $row->COA ?? 'Gerai' }}</span></td>
                                <td class="py-4 text-right">Rp {{ number_format($row->grand_total    ?? $row->Nominal, 0, ',', '.') }}</td>
                                @php $totalRow += ($row->grand_total     ?? $row->Nominal); @endphp
                            @endif

                        </tr>
                        @endforeach
                    </tbody>
                    
                    {{-- Footer Kalkulasi Otomatis --}}
                    @if(request('type') != 'stok' && request('type') != 'anggota' && request('type') != 'labarugi' && request('type') != 'neraca')
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="{{ request('type') == 'piutang' ? 3 : 4 }}" class="py-5 text-center uppercase text-[10px] tracking-widest font-black">Total Akumulasi Periode</td>
                            @if(request('type') == 'piutang')
                                <td class="py-5 text-right font-black text-slate-400">Rp {{ number_format($totalRow, 0, ',', '.') }}</td>
                                <td class="py-5 text-right font-black text-red-400 text-lg">Rp {{ number_format($totalSec, 0, ',', '.') }}</td>
                            @else
                                <td class="py-5 text-right font-black text-red-400 text-lg">Rp {{ number_format($totalRow, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    </tfoot>
                    @elseif(isset($summary) && count($summary) > 0)
                    <tfoot>
                        <tr class="bg-slate-900 text-white">
                            <td colspan="5" class="py-6 px-8">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($summary as $key => $value)
                                    <div class="bg-slate-800 p-4 rounded-lg">
                                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2">{{ str_replace('_', ' ', $key) }}</p>
                                        <p class="text-red-400 text-lg font-black">{{ is_numeric($value) ? 'Rp ' . number_format($value, 0, ',', '.') : $value }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @else
            {{-- Tampilan Saat Data Kosong --}}
            <div class="bg-white p-24 rounded-3xl shadow-xl text-center border border-slate-100">
                <div class="mb-6 inline-flex p-6 bg-slate-50 rounded-full text-slate-200">
                    <i class="fas fa-folder-open text-6xl"></i>
                </div>
                <p class="text-slate-400 font-black italic uppercase tracking-widest text-lg">Belum ada data untuk laporan ini</p>
                <p class="text-slate-300 text-sm mt-2">Silakan pilih jenis laporan dan rentang tanggal di atas.</p>
            </div>
        @endif

    </div>
</div>

{{-- CSS KHUSUS PRINT --}}
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; padding: 0 !important; }
        .max-w-7xl { max-width: 100% !important; width: 100% !important; padding: 0 !important; }
        .rounded-3xl { border-radius: 0 !important; }
        .shadow-2xl { box-shadow: none !important; }
    }
</style>
@endsection