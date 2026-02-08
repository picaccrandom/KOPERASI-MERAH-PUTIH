@extends('layouts.master')

@section('content')
    <div class="max-w-4xl mx-auto bg-white border border-black p-6 font-mono text-sm">

        <!-- HEADER -->
        <div class="flex justify-between items-start border-b border-black pb-3 mb-4">
            <div class="flex gap-3">
                <img src="/logo.png" alt="Logo" class="h-16">
                <div>
                    <h1 class="font-bold text-lg uppercase">Koperasi Simpan Pinjam</h1>
                    <p class="font-semibold">Bissmika Usaha Makmur Nusantara</p>
                </div>
            </div>
            <div class="text-right text-xs leading-tight">
                <p class="font-bold">Kantor :</p>
                <p>Jalan I Gusti Ngurah Rai, Rukan No. 07</p>
                <p>RT.02/RW.07 Kel. Pondok Kopi</p>
                <p>Kec. Duren Sawit Jakarta Timur 13460</p>
            </div>
        </div>

        <!-- TITLE -->
        <h2 class="text-center font-bold text-lg underline mb-6 tracking-widest">
            BUKTI PEMBAYARAN ANGSURAN
        </h2>

        <!-- INFO -->
        <div class="grid grid-cols-2 gap-10 mb-4">
            <div class="space-y-1">
                <p>Jenis Angsuran : <span class="font-semibold">Bulanan</span></p>
                <p>Nama Peminjam : <span class="font-semibold">Rahmat Hidayat</span></p>
                <p>Jumlah Angsuran : <span class="font-semibold">12x</span></p>
            </div>
            <div class="space-y-1">
                <p>Kode Pinjaman : <span class="font-semibold">KSP-201464</span></p>
                <p>Tanggal : <span class="font-semibold">10/10/2022</span></p>
                <p>Angsuran : <span class="font-semibold">9</span></p>
            </div>
        </div>

        <!-- TERBILANG -->
        <div class="mb-4">
            <p>Terbilang :</p>
            <p class="font-semibold italic ml-24">
                Rp. 1.200.000,- <br>
                (Satu Juta Dua Ratus Ribu Rupiah)
            </p>
        </div>

        <!-- GARIS -->
        <div class="border-t border-dashed border-black my-6"></div>

        <!-- TANDA TANGAN -->
        <div class="grid grid-cols-2 text-center mt-8">
            <div>
                <p>Kasir,</p>
                <div class="h-20"></div>
                <p class="border-t border-black inline-block px-10">
                    (.................................)
                </p>
            </div>
            <div>
                <p>Penyetor,</p>
                <div class="h-20 relative">
                    <!-- stamp optional -->
                    <!-- <img src="/stamp.png" class="absolute inset-0 mx-auto opacity-50 h-20"> -->
                </div>
                <p class="border-t border-black inline-block px-10 font-semibold">
                    RAHMAT HIDAYAT
                </p>
            </div>
        </div>

    </div>
@endsection
