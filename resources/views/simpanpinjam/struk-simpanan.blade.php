@extends('layouts.master')




@section('content')
        {{-- @php
        dd($transaksi->simpananDetails)
    @endphp --}}
    <main class="font-inter print-area">
        <div class="w-full mx-auto bg-white px-16 py-6 border border-dashed text-sm font-mono shadow rounded">

            <!-- HEADER / KOP -->
            <div class="relative border-b pb-2 mb-4">
                <div class="flex items-center">

                    <!-- Logo -->
                    <img src="{{ asset('img/logo-koperasi.png') }}" alt="Logo Koperasi" class="w-20 h-20 object-contain">

                    <!-- Judul -->
                    <div class="flex-1 text-center leading-tight">
                        <p class="my-1 text-3xl font-black uppercase tracking-wide">
                            Koperasi Merah Putih
                        </p>
                        <p class="my-1 text-xs">
                            Badan Hukum No. 50/BH/IV/DINASKOP/IX/2014
                        </p>
                        <p class="my-1 text-xs">
                            Unit 102 Nangsri, Jl. Hang Tuah Pasar Rabu
                        </p>
                        <p class="mt-1 mb-0 text-xs font-semibold">
                            Kebakramat – Karanganyar
                        </p>
                    </div>

                    <!-- Jenis Simpanan -->
                    <div class="absolute top-0 right-0 border border-dotted px-3 uppercase py-1 text-xs font-semibold">
                        @if ($modul === 'Penarikan')
                            Penarikan Simpanan
                        @else
                            Simpanan {{ $transaksi->simpananDetails->first()->jenis }}
                        @endif
                    </div>
                </div>
            </div>

            <!-- INFO TRANSAKSI -->
            <div class="flex justify-between mb-3">
                <div class="">
                    <p class="my-1"><span class="font-semibold ">No. Transaksi:</span> {{ $transaksi->no_transaksi_sp }}
                    </p>
                    <p class="my-1"><span class="font-semibold ">ID Petugas:</span>{{ $user->id }}</p>
                    <p class="my-1"><span class="font-semibold ">Nama Petugas:</span> {{ $user->name }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold">{{ $transaksi->tanggal }}</p>
                </div>
            </div>

            <div class="border-t border-dashed my-3"></div>

            <!-- DETAIL -->
            <div class="flex">

                <!-- DATA ANGGOTA -->
                <div class="w-1/2 border border-dotted p-3 space-y-2">
                    <p class="text-base">
                        <span class="font-semibold">Nomor Induk Kependudukan:</span><br>
                        {{ $transaksi->member->nik }}
                    </p>
                    <p class="text-base">
                        <span class="font-semibold">Nama Anggota:</span><br>
                        {{ $transaksi->nama }}
                    </p>
                </div>

                <!-- DETAIL SIMPANAN -->
                <div class="w-1/2 border border-dashed p-3 space-y-2">
                    <div class="flex justify-between">
                        <span>Nominal {{ $modul }}</span>Rp.
                        <span id="nominal">{{ number_format($transaksi->Nominal, 2, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Administrasi </span>
                        <span class="text-red-600">
                            Rp <span id="nominal"> {{ number_format($transaksi->simpananDetails->first()->biaya_admin, 2, ',', '.') }}</span>
                        </span>
                    </div>

                    <div class="flex justify-between items-center gap-2">
                        <div class="border-t border-dashed my-2 w-[90%]"></div>
                        <strong>-</strong>
                    </div>

                    <div class="flex justify-between items-center font-bold">
                        <span>Total {{ $modul }}</span>
                        <span class="bg-slate-200 px-3 py-1 text-2xl">Rp. 
                            <span class="border-b border-dashed" id="nominal">
                                @if ($modul === 'Penarikan')
                                    {{ number_format(abs($transaksi->simpananDetails->first()->saldo), 2, ',', '.') }}
                                @else
                                    {{ number_format($transaksi->simpananDetails->first()->saldo, 2, ',', '.') }}</span>
                                @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="text-center mt-3">
                <div class="justify-self-end flex justify-end gap-16">
                    <div class="mt-3">
                        <p>Petugas</p>
                        <p class="font-semibold mt-7">______________</p>
                    </div>
                    <div class="mt-3">
                        <p>Member</p>
                        <p class="font-semibold mt-7">______________</p>
                    </div>
                </div>
                <p>Terima kasih</p>
                <p class="text-xs">
                    Simpan struk ini sebagai bukti transaksi
                </p>

            </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            
            window.print();
            if (window.location.href.indexOf('print') > -1) {
                setTimeout(() => {
                    window.close();
                }, 500);
            }else{
                setTimeout(() => {
                    window.location.href = "{{ route('simpanan.index') }}";
                }, 500);
            }
            
        });
    </script>
@endsection
