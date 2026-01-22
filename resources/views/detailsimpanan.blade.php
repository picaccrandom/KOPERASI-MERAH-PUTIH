@extends('layouts.master')

@section('title', 'Detail Simpanan - Koperasi Merah Putih')

@section('content')
    <div class="mx-10 px-4 bg-white/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class="px-6 py-2 flex justify-between items-center">
                <div class="border-l-8 border-l-blue-500 pl-4">
                    <div class="text-2xl text-white text-4xl text-shadow-lg uppercase font-extrabold tracking-wider">
                        Detail Simpanan
                    </div>
                    <p class="text-slate-600 mt-2">Kode: {{ $transaksi->no_transaksi_sp }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('simpanan.index') }}" 
                       class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded flex items-center text-decoration-none shadow-md text-lg uppercase font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-6">

        <!-- Informasi Simpanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-user-circle mr-2"></i>Informasi Anggota
                </h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Nama Lengkap:</span>
                        <span class="w-2/3">{{ $transaksi->member->nama_lengkap }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Nomor Anggota:</span>
                        <span class="w-2/3">{{ $transaksi->member->nomor_anggota ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Alamat:</span>
                        <span class="w-2/3">{{ $transaksi->member->alamat ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Telepon:</span>
                        <span class="w-2/3">{{ $transaksi->member->telepon ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Simpanan
                </h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Kode Transaksi:</span>
                        <span class="w-2/3 font-mono bg-gray-100 px-2 py-1 rounded">{{ $transaksi->no_transaksi_sp }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Tanggal:</span>
                        <span class="w-2/3">{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y H:i') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Jenis:</span>
                        <span class="w-2/3">
                            @if($transaksi->simpananDetails->first()->jenis == 'wajib')
                                <span class="px-2 py-1 text-xs font-semibold uppercase rounded bg-blue-100 text-blue-800">
                                    Wajib
                                </span>
                            @elseif($transaksi->simpananDetails->first()->jenis == 'pokok')
                                <span class="px-2 py-1 text-xs font-semibold uppercase rounded bg-green-100 text-green-800">
                                    Pokok
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold uppercase rounded bg-orange-100 text-orange-800">
                                    Sukarela
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 font-semibold text-gray-700">Status:</span>
                        <span class="w-2/3">
                            @if($transaksi->simpananDetails->first()->status == 'aktif')
                                <span class="px-2 py-1 text-xs font-semibold uppercase rounded bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold uppercase rounded bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-receipt mr-2"></i>Detail Transaksi
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit/Kredit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $transaksi->Keterangan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs font-semibold rounded 
                                    {{ $transaksi->{'Debit/Credit'} == 'Debit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaksi->{'Debit/Credit'} }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                Rp {{ number_format($transaksi->Nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}
                            </td>
                        </tr>
                        @foreach($transaksi->simpananDetails as $detail)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $loop->iteration + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                Simpanan {{ ucfirst($detail->jenis) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                Rp {{ number_format($detail->saldo, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ \Carbon\Carbon::parse($detail->tanggal)->format('d F Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-700 text-right">Total:</td>
                            <td class="px-6 py-4 text-sm font-bold text-red-600">
                                Rp {{ number_format($transaksi->simpananDetails->sum('saldo'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Catatan -->
        @if($transaksi->Keterangan)
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-md font-bold text-blue-800 mb-2">
                <i class="fas fa-sticky-note mr-2"></i>Catatan:
            </h4>
            <p class="text-gray-700">{{ $transaksi->Keterangan }}</p>
        </div>
        @endif
    </div>
@endsection