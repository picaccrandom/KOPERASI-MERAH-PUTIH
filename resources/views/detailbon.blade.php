@extends('layouts.master')
@section('title', 'Detail Pinjaman')

@section('content')
    {{-- @php
        dd($bon)
    @endphp --}}
    <div class="relative bg-white/40 backdrop-blur-2xl rounded-lg mx-32 p-16">
        <div class="border-l-8 border-l-green-400 pl-4 mb-8">
            <div class="border-b-2 pb-2 mb-2 border-b-slate-500 inline-block text-4xl font-bold text-white uppercase shadow-sm">
                Detail <span class="px-1 bg-black text-white rounded-md shadow-md">BON</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg px-14 py-8">
            <table class="w-full table-auto text-center border-collapse">
                <thead class="bg-black text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3">NO BON</th>
                        <th>NAMA ANGGOTA</th>
                        <th>TANGGAL BON</th>
                        <th>JUMLAH BON</th>
                        <th>KETERANGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-gray-50 text-sm">
                        <td class="py-4">{{ $bon->no_transaksi_sp }}</td>
                        <td class="text-center">{{ $bon->member->nama_lengkap }}</td>
                        <td>{{ \Carbon\Carbon::parse($bon->created_at)->format('d M Y') }}</td>
                        <td class="font-bold text-red-600">Rp {{ number_format($bon->Nominal, 0, ',', '.') }}</td>
                        <td>{{ $bon->keterangan ?? 'tidak ada keterangan' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 flex gap-3 justify-end">
                <a href="{{ route('bon.indexBon') }}" class="text-decoration-none  mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
                <form action="{{ route('bon.bayarBon', $bon->no_transaksi_sp) }}" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="text-decoration-none  mt-4 inline-block bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">Lunasi Bon</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection