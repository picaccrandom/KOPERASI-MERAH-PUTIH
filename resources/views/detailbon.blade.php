@extends('layouts.master')
@section('title', 'Detail Pinjaman')

@section('content')
    {{-- @php
        dd($bon)
    @endphp --}}
    <div class="relative bg-white/40 backdrop-blur-2xl rounded-lg mx-32 p-16">
        <div class="border-l-8 border-l-green-400 pl-4 mb-8">
            <div
                class="border-b-2 pb-2 mb-2 border-b-slate-500 inline-block text-5xl font-extrabold text-white uppercase text-shadow-md text-shadow-amber-300 shadow-sm">
                Detail <span class="px-1 py-0 bg-black  text-white rounded-md shadow-md">BON</span>
            </div>
        </div>

        <div class="w-full bg-white rounded-3xl shadow-md overflow-hidden">
            {{-- tampil Data Simpanan akan ditempatkan di sini --}}
            <div class="text-2xl mb-4 border-b pb-2 bg-orange-300 px-4 pt-3 uppercase tracking-wide font-extrabold text-white shadow-md">
                <i class="fas fa-user-circle mr-2"></i>Informasi Bon Anggota
            </div>
            <div class="p-10">
                <!-- Info Anggota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class=" space-y-8">
                        <div>
                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                            <p class="text-lg font-bold text-gray-800" id="nama-anggota">{{ $bon->member->nama_lengkap }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="text-lg text-gray-800" id="nik">{{ $bon->member->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NO. TELP/WA</p>
                            <p class="text-lg text-gray-800" id="no-telp">{{ $bon->member->nomor_hp }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Total BON</p>
                            <p class="text-lg font-bold text-red-600">
                                Rp. <span id="total_pinjaman">{{ number_format($bon->Nominal, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal BON</p>
                            <div class="bg-green-600 px-2 shadow-md inline-block rounded-md">
                                <p class="text-white m-0" id="jenis-pinjaman">
                                    {{ \Carbon\Carbon::parse($bon->created_at)->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-6 p-4 bg-orange-50 rounded">
                    <p class="text-sm text-gray-500 mb-1">Catatan</p>
                    <p class="text-gray-800" id="alamat">{{ $bon->Keterangan ?? '-' }}</p>
                </div>

                <div class="mt-4 flex gap-3 justify-end">
                    <a href="{{ route('bon.indexBon') }}"
                        class="text-decoration-none  mt-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Kembali</a>
                    <form action="{{ route('bon.bayarBon', $bon->no_transaksi_sp) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit"
                            class="text-decoration-none  mt-4 inline-block bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">Lunasi
                            Bon</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
@endsection
