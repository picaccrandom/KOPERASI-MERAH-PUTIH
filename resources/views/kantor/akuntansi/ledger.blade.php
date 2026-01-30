@extends('layouts.master')

@section('content')
<div class="p-10 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8 border-b-4 border-slate-800 pb-4">
            <h1 class="text-4xl font-black text-slate-800 uppercase tracking-tighter">Buku Besar</h1>
            <p class="text-slate-500 font-bold uppercase text-sm">Akun: {{ $account->nama_akun }} ({{ $account->kode_akun }})</p>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-800 text-white uppercase text-xs tracking-widest">
                    <tr>
                        <th class="p-6">Tanggal</th>
                        <th class="p-6">Keterangan</th>
                        <th class="p-6">Referensi</th>
                        <th class="p-6 text-right">Debit</th>
                        <th class="p-6 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($account->jurnals as $j)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-6">{{ $j->tgl_transaksi }}</td>
                        <td class="p-6">{{ $j->keterangan }}</td>
                        <td class="p-6 font-mono text-blue-600">{{ $j->referensi }}</td>
                        <td class="p-6 text-right text-emerald-600">{{ number_format($j->debit, 0, ',', '.') }}</td>
                        <td class="p-6 text-right text-rose-600">{{ number_format($j->kredit, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-100 font-black text-slate-900 border-t-4 border-slate-200">
                    <tr>
                        <td colspan="3" class="p-6 text-right uppercase tracking-widest">Saldo Akhir Real-time:</td>
                        <td colspan="2" class="p-6 text-right text-2xl">Rp {{ number_format($account->saldo_akhir, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection