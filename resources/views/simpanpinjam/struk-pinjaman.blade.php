@extends('layouts.master')

@section('content')
    {{-- @php
    dd($angsuran, $pinjaman, $no_transaksi_sp, $loc);
@endphp --}}
    <main class="print-area">
        <div class="max-w-4xl mx-auto bg-white border border-black p-6 pt-12 font-mono text-sm relative">
            <span class="absolute top-3 right-3 px-2 py-1 text-xs border border-slate-300">{{ $no_transaksi_sp }}</span>
            <!-- HEADER -->
            <div class="flex justify-between items-start border-b border-black pb-1 mb-4">
                <div class="flex gap-3">
                    <img src="{{ asset('img/logo-koperasi.png') }}" alt="Logo" class="h-16">
                    <div>
                        <span class="font-bold text-3xl uppercase">Koperasi Simpan Pinjam</span>
                        <p class="font-semibold">Unit 012 | Kecamatan Nangsri</p>
                    </div>
                </div>
                <div class="text-right text-xs leading-tight ">
                    <p class="font-bold my-0">Kantor :</p>
                    <p class="my-0">Jalan I Gusti Ngurah Rai, Rukan No. 07</p>
                    <p class="my-0">RT.02/RW.07 Kel. Pondok Kopi</p>
                    <p class="my-0">Kec. Duren Sawit Jakarta Timur 13460</p>
                </div>
            </div>
            @if ($modul == 'Angsuran')
                {{-- Format Untuk Angsuran --}}
                <!-- TITLE -->
                <div class="text-center font-extrabold text-3xl underline mb-6 tracking-widest">
                    BUKTI PEMBAYARAN ANGSURAN
                </div>

                <!-- INFO -->
                <div class="grid grid-cols-2 mb-4 text-sm">
                    <div class="col-span-1 border-x border-y border-dashed p-3 pr-4">
                        <p class="my-0">Jenis Pinjaman : <span class="font-semibold">Bulanan</span></p>
                        <p class="my-0">Nama Peminjam : <span
                                class="font-semibold">{{ $pinjaman->member->nama_lengkap }}</span></p>
                        <p class="my-0">Jumlah Angsuran : <span class="font-semibold">{{ $angsuran->tenor }} X</span></p>
                    </div>
                    <div class="col-span-1 border-x border-y border-dashed p-3 pl-4">
                        <p class="my-0">Kode Pinjaman : <span
                                class="font-semibold">{{ $angsuran->no_transaksi_sp }}</span></p>
                        <p class="my-0">Tanggal : <span class="font-semibold">{{ $angsuran->tanggal_bayar }}</span></p>
                        <p class="my-0">Angsuran : <span class="font-semibold">{{ $angsuran->angsuran_ke }}</span></p>
                    </div>
                </div>

                <!-- TERBILANG -->
                <div class="mb-2 flex items-start">
                    <p>Terbilang :</p>
                    <p class="font-semibold italic ml-24 text-xl mb-0 ">
                        Rp. <span>{{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}</span>,- <br>
                        <span class="text-base">({{ ucwords(Terbilang::make($angsuran->jumlah_angsuran)) }} Rupiah)</span>
                    </p>
                </div>

                <!-- GARIS -->
                <div class="border-t border-dashed border-black my-6"></div>

                <!-- TANDA TANGAN -->
                <div class="grid grid-cols-2 text-center mt-16">
                    <div>
                        <p>Petugas,</p>
                        <div class="h-20 relative">
                            <img src="{{ asset('img/ttd-example.png') }}" alt="ttd"
                                class="mx-auto h-56 absolute -top-20 left-0 right-0">

                        </div>
                        <p class="border-t border-black inline-block px-10 ">
                            ( {{ Auth()->user()->name ?? 'Petugas Anomali' }} )
                        </p>
                    </div>
                    <div>
                        <p>Penyetor,</p>
                        <div class="h-20 relative">
                            <!-- stamp optional -->
                            <!-- <img src="/stamp.png" class="absolute inset-0 mx-auto opacity-50 h-20"> -->
                        </div>
                        <p class="border-t border-black inline-block px-10 font-semibold">
                            ( {{ $pinjaman->member->nama_lengkap }} )
                        </p>
                    </div>
                </div>
            @elseif ($modul == 'Pinjaman')
                {{-- Format Untuk Pinjaman --}}
                <!-- TITLE -->
                <div class="text-center text-3xl font-bold underline mb-4">
                    SURAT PENERIMAAN PINJAMAN
                </div>

                <!-- DATA -->
                <div class="mb-4 space-y-1">
                    <div class="flex">
                        <span class="w-40">NIK</span>
                        <span id="nominal-pinjaman">: {{ $pinjaman->member->nik }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-40">Nama</span>
                        <span>: {{ $pinjaman->member->nama_lengkap }}</span>
                    </div>
                </div>

                <!-- POTONGAN -->
                <table class="w-full mb-2 border-collapse">
                    <tbody>
                        <tr>
                            <td colspan="2">Pelunasan</td>
                            <td class="text-right font-bold" id="pelunasan">Rp
                                {{ number_format($angsuran->first()->total_pinjaman, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Besaran Pinjaman</td>
                            <td class="text-right font-bold" id="besaran-pinjaman">Rp
                                {{ number_format($pinjaman->Nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Potongan</td>
                            <td colspan="2" class="pl-2">
                                <hr>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 w-6">1.</td>
                            <td>Administrasi 0.2%</td>
                            <td class="text-right" id="admin">Rp
                                {{ number_format($pinjaman->Nominal * 0.02, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">2.</td>
                            <td>Materai / MAP / Foto Agunan</td>
                            <td class="text-right" id="materai">Rp 10.000</td>
                        </tr>
                        {{-- <tr>
                            <td class="py-1">3.</td>
                            <td>Angsuran 1 Bulan</td>
                            <td class="text-right" id="angsuran">Rp
                                {{ number_format($angsuran->first()->jumlah_angsuran ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">4.</td>
                            <td>Denda</td>
                            <td class="text-right" id="denda">Rp
                                {{ number_format($angsuran->first()->denda ?? 0, 0, ',', '.') }}</td>
                        </tr> --}}
                        <tr>
                            <td class="py-1">3.</td>
                            <td>Mitra 0,1%</td>
                            <td class="text-right" id="mitra">Rp
                                {{ number_format($pinjaman->Nominal * 0.01, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- TOTAL -->
                <div class="border-t border-black pt-2 mb-6 space-y-1">
                    <div class="flex justify-between font-bold">
                        <span class="border-b border-black pb-1">JUMLAH POTONGAN</span>
                        <span id="potongan">Rp</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>JUMLAH PENERIMAAN</span>
                        <span id="penerimaan">Rp</span>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="flex justify-between mb-8">
                    <div>
                        <p class="pl-24">Mengetahui,</p>
                    </div>
                    <div class="text-right">
                        <p>{{ $loc }} , {{ now()->format('d F Y') }}</p>
                        <p>Dikeluarkan Oleh,</p>
                    </div>
                </div>

                <!-- SIGNATURE -->
                <div class="flex justify-between text-center mt-12">
                    <div class="w-1/3 flex flex-col items-center">
                        <div class="">( Subagyo NotoHusodo )</div>
                        <div class="border-t border-black border-dashed pt-1 font-bold w-[80%]">MANAGER</div>
                    </div>
                    <div class="w-1/3 flex flex-col items-center">
                        <div class="">( {{ $pinjaman->member->nama_lengkap }} )</div>
                        <div class="border-t border-black border-dashed pt-1 font-bold w-[80%]">NASABAH</div>
                    </div>
                    <div class="w-1/3 flex flex-col items-center">
                        <div class="">( {{ auth()->user()->name }} )</div>
                        <div class="border-t border-black border-dashed pt-1 font-bold w-[80%]">KASIR</div>
                    </div>
                </div>
            @endif
            <div class="mt-6">
                <p class="text-center text-xs mt-6 mb-2">*** Terima Kasih Telah Percaya dengan Jasa Kami
                    ***</p>
                <p class="text-center text-xs my-0">*** Cek Kembali Nominal dan Tanggal Pembayaran, Pastiikan Sesuai
                    dengan Angsuran Sebenarnya ***</p>
            </div>

        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $modul = "{{ $modul }}";
            if ($modul == 'Pinjaman') {
                document.title = "Struk Pinjaman {{ $no_transaksi_sp }}";
    
                // Dapatkan nilai nominal pinjaman
                const nominalPinjaman = parseFloat(document.getElementById('besaran-pinjaman').textContent.replace(
                    /[^0-9,-]+/g, "").replace(
                    ',', '.')) || 0;
                    
                // Hitung total potongan
                const admin = parseFloat(document.getElementById('admin').textContent.replace(/[^0-9,-]+/g, "").replace(
                    ',', '.')) || 0;
                const materai = parseFloat(document.getElementById('materai').textContent.replace(/[^0-9,-]+/g, "")
                    .replace(',', '.')) || 0;
                // const angsuran = parseFloat(document.getElementById('angsuran').textContent.replace(/[^0-9,-]+/g, "")
                //     .replace(',', '.')) || 0;
                // const denda = parseFloat(document.getElementById('denda').textContent.replace(/[^0-9,-]+/g, "").replace(
                //     ',', '.')) || 0;
                const mitra = parseFloat(document.getElementById('mitra').textContent.replace(/[^0-9,-]+/g, "").replace(
                    ',', '.')) || 0;
    
                const totalPotongan = admin + materai + mitra;
    
                // Tampilkan total potongan
                document.getElementById('potongan').textContent = '- Rp ' + totalPotongan.toLocaleString('id-ID');
                
                // Tampilkan jumlah penerimaan
                const jumlahPenerimaan = nominalPinjaman - totalPotongan;
                console.log(nominalPinjaman, totalPotongan, jumlahPenerimaan);
                document.getElementById('penerimaan').textContent = 'Rp ' + jumlahPenerimaan.toLocaleString('id-ID');
            }
            
            // otomatis cetak struk saat halaman dimuat
            window.print();
            if (window.location.href.indexOf('print') > -1) {
                setTimeout(() => {
                    window.close();
                }, 500);
            } else {
                setTimeout(() => {
                    if($modul == 'Pinjaman') {
                        window.location.href = "{{ route('pinjaman.index') }}";
                    } else {
                        window.location.href = `{{ route('pinjaman.detail',$angsuran->no_transaksi_sp) }}`;
                    }
                }, 500);
            }

        });
    </script>
@endsection
