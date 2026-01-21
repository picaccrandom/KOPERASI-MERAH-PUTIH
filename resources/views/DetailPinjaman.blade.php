@extends('layouts.master')

@section('title', 'Detail Pinjaman - Koperasi Merah Putih')

@section('content')
{{-- @php
    dd($pinjaman)
@endphp --}}
    <div class="relative bg-white/40 backdrop-blur-2xl rounded-lg mx-32 p-16">
        <div class=" border-l-8 border-l-green-400 pl-4 mb-8">
            <div
                class="border-b-2 pb-2 mb-2 border-b-slate-500 inline-block text-4xl font-bold text-white text-shadow-lg uppercase tracking-wider">
                Detail <span class="px-1 bg-black text-white rounded-md shadow-md">Pinjaman & Angsuran</span></div>
            <p class="text-sm text-slate-400">Detail Peminjaman Member</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg px-14 py-8">
            <div class="p-6">
                <!-- Info Anggota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="text-lg text-gray-800">{{ $pinjaman->member->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NAMA ANGGOTA</p>
                            <p class="text-lg font-bold text-gray-800">{{ $pinjaman->member->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">TEMPAT, TANGGAL LAHIR</p>
                            <p class="text-lg text-gray-800">{{ $pinjaman->member->tempat_lahir }},
                                {{ $pinjaman->member->tanggal_lahir }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NO. TELP/WA</p>
                            <p class="text-lg text-gray-800">{{ $pinjaman->member->nomor_hp }} / WhatsApp</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">TOTAL PINJAMAN</p>
                            <p class="text-lg font-bold text-red-600">Rp. {{ number_format($pinjaman->total_pinjaman, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">JENIS PINJAMAN</p>
                            <p class="text-md  gap-2 flex items-center">
                                @if ($pinjaman->jenis == 'uang')
                                    <span
                                        class="px-2 py-1 font-semibold rounded bg-blue-100 text-blue-800 uppercase">{{ ucfirst($pinjaman->jenis) }}</span>
                                @else
                                    <span
                                        class="px-2 py-1 font-semibold rounded bg-green-100 text-green-800 uppercase">{{ ucfirst($pinjaman->jenis) }}</span>
                                @endif
                                / {{ $pinjaman->tenor }} Bulan
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">TOTAL DIBAYAR</p>
                            <p class="text-lg font-bold text-green-600">Rp. {{ number_format($pinjaman->angsuranPeminjamans->sum('jumlah_bayar'), 0, ',', '.') }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">JATUH TEMPO</p>
                                <p class="text-lg text-gray-800">
                                    {{ Carbon\Carbon::parse($pinjaman->tanggal_jatuh_tempo)->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 ">STATUS PINJAMAN</p>
                                <span class="flex justify-center">
                                    @if ($pinjaman->status == 'aktif')
                                        <span
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $pinjaman->status }}</span>
                                    @elseif ($pinjaman->status == 'lunas')
                                        <span
                                            class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $pinjaman->status }}</span>
                                    @else
                                        <span
                                            class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">{{ $pinjaman->status }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <p class="text-sm text-gray-500 mb-1">ALAMAT</p>
                    <p class="text-gray-800">{{ $pinjaman->member->alamat }}</p>
                </div>

                <!-- Angsuran Table -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Rincian Angsuran</h4>
                        <div class="flex items-center space-x-3">
                            <button id="btn-rincian-angsuran"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                    Buka Rincian Angsuran
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto hidden max-h-48 overflow-y-auto relative" id="rincian-angsuran">
                        <table class="w-full table-auto border-collapse text-center">
                            <thead class="bg-black text-white sticky top-0">
                                <tr>
                                    <th>ANGSURAN KE</th>
                                    <th>BATAS BAYAR</th>
                                    <th>NOMINAL HARUS DIBAYAR</th>
                                    <th>TANGGAL BAYAR</th>
                                    <th>STATUS BAYAR</th>
                                    <th>TRANSAKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pinjaman->angsuranPeminjamans as $angsuran)
                                    <tr class="[&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50">
                                        {{-- @php
                                            dd($angsuran);
                                        @endphp --}}
                                        <td class="text-center">{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ Carbon\Carbon::parse($angsuran->batas_bayar)->format('d M Y') }}</td>
                                        <td class="font-bold text-red-600">Rp
                                            {{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($angsuran->tanggal_bayar)
                                                {{ Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($angsuran->status == 'lunas')
                                                <span
                                                    class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">LUNAS</span>
                                            @else
                                                <span
                                                    class="bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold uppercase shadow-md">BELUM
                                                    LUNAS</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex justify-center items-center gap-3">
                                                @if ($angsuran->status == 'belum')
                                                    <button
                                                        onclick="bayarAngsuran({{ $angsuran->id }}, {{ $angsuran->angsuran_ke }}, {{ $pinjaman->member->id }})"
                                                        class="text-green-600 hover:text-green-900" title="Bayar">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                @endif
                                                <button class="text-blue-600 hover:text-blue-900" title="Cetak">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>

        // Toggle Rincian Angsuran
        document.getElementById('btn-rincian-angsuran').addEventListener('click', function() {
            var rincianAngsuran = document.getElementById('rincian-angsuran');
            if (rincianAngsuran.classList.contains('hidden')) {
                rincianAngsuran.classList.remove('hidden');
                // document.getElementById('btn-rincian-angsuran').classList.add('bg-orange-600');
                // document.getElementById('btn-rincian-angsuran').remove('bg-blue-600');
                this.textContent = 'Tutup Rincian Angsuran';
            } else {
                rincianAngsuran.classList.add('hidden');
                // document.getElementById('btn-rincian-angsuran').classList.add('bg-blue-600');
                // document.getElementById('btn-rincian-angsuran').classList.remove('bg-orange-600');
                this.textContent = 'Buka Rincian Angsuran';
            }
        });

        function bayarAngsuran(angsuranId, angsuranKe, memberId) {
            swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin membayar angsuran ke - ' + angsuranKe + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Bayar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/pinjaman/bayar-angsuran/${memberId}/${angsuranId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json());
                    swal.fire(
                        'Berhasil!',
                        'Angsuran ke - ' + angsuranKe + ' telah dibayar.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                }
            });
                
        };
    </script>
@endsection