@extends('layouts.master')

@section('title', 'Detail Pinjaman')

@section('content')

    <div class="relative bg-orange-900/40 backdrop-blur-2xl rounded-lg mx-12 p-12">
        <div class="border-l-8 border-l-orange-400 pl-4 mb-8">
            <div class=" text-white flex flex-col gap-3 ">
               <span class="uppercase text-4xl"> Detail <span class="px-2 bg-orange-400 rounded-md shadow-md font-bold">Pinjaman & Angsuran</span></span> 
                <p class="italic text-base">Pastikan data di bawah ini benar dan sesuai dengan data anggota !</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg px-14 py-8">
            <div class="pl-6">
                {{-- Info Pinjaman & Anggota --}}
                <span class="text-2xl uppercase font-bold text-gray-800 mb-4">
                    <i class="fa-solid fa-circle-user mr-4"></i>
                    Informasi Pinjaman & Anggota
                </span>
            </div>
            <div class="p-6">
                
                <!-- Info Anggota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 bg-slate-400/20 p-6 rounded-lg">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">KODE PINJAMAN</p>
                            <p class="text-lg text-gray-800">{{ $transaksiInduk->no_transaksi_sp }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NAMA ANGGOTA</p>
                            <p class="text-lg font-bold text-gray-800">{{ $transaksiInduk->nama }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="text-lg text-gray-800">{{ $transaksiInduk->member->nik }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NO. TELP/WA</p>
                            <p class="text-lg text-gray-800">{{ $transaksiInduk->member->nomor_hp }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">TOTAL PINJAMAN</p>
                            <p class="text-lg font-bold text-red-600">
                                Rp {{ number_format($pinjaman->first()->total_pinjaman ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">JENIS PINJAMAN</p>
                            <div class="bg-orange-600 px-2 shadow-md inline-block rounded-md">
                                <p class="text-white m-0">{{ $transaksiInduk->COA }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">BUNGA / TENOR</p>
                            <p class="text-lg text-gray-800">
                                {{ $pinjaman->first()->bunga ?? 0 }}% / {{ $pinjaman->first()->tenor ?? 0 }} Bulan
                            </p>
                        </div>
                        <div class="flex space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">JATUH TEMPO TERDEKAT</p>
                                <p class="text-lg text-gray-800">
                                    {{ \Carbon\Carbon::parse($pinjaman->where('status', 'belum')->first()->batas_bayar ?? now())->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="mb-6 p-4 bg-gray-100 rounded">
                    <p class="text-sm text-gray-500 mb-1">ALAMAT</p>
                    <p class="text-gray-800">{{ $transaksiInduk->member->alamat }}</p>
                </div>
                <div class="mb-4">
                    <div class="flex justify-start items-center uppercase  text-2xl mb-4 font-semibold">
                        <i class="fa-solid fa-table-list mr-2"></i>
                        <span class="text-lg font-bold text-gray-800">Rincian Angsuran</span>
                    </div>

                    <div class="overflow-x-auto max-h-64 relative border rounded" id="rincian-angsuran">
                        <table class="w-full table-auto text-center border-collapse">
                            <thead class="bg-black text-white sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3">ANGSURAN KE</th>
                                    <th>BATAS BAYAR</th>
                                    <th>NOMINAL</th>
                                    <th>TANGGAL BAYAR</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pinjaman as $angsuran)
                                    <tr class="border-b hover:bg-gray-50 text-sm">
                                        <td class="py-4">{{ $angsuran->angsuran_ke }}</td>
                                        <td>{{ \Carbon\Carbon::parse($angsuran->batas_bayar)->format('d M Y') }}</td>
                                        <td class="font-bold text-red-600">Rp {{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}</td>
                                        <td>{{ $angsuran->tanggal_bayar ? \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d M Y') : '-' }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded text-xs font-semibold text-white {{ $angsuran->status == 'lunas' ? 'bg-green-600' : 'bg-red-600' }}">
                                                {{ strtoupper($angsuran->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($angsuran->status == 'belum')
                                                <button onclick="bayarAngsuran({{ $angsuran->id }}, {{ $angsuran->angsuran_ke }}, {{ $transaksiInduk->member_id }})" class="text-green-600 hover:text-green-800 scale-125 transition inline-block">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <a href="{{ route('pinjaman.index') }}" class="text-decoration-none group mt-4 inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                <i class="fa-solid fa-angles-left mr-1 group-hover:mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('scripts')
<script>

    // cegah melewati angsuran pertama
    document.addEventListener('DOMContentLoaded', () => {
        const rincianAngsuran = document.getElementById('rincian-angsuran');
        const rows = rincianAngsuran.querySelectorAll('tbody tr');
        let firstUnpaidFound = false;
    
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(5)');
            if (statusCell && statusCell.textContent.trim() === 'BELUM') {
                if (!firstUnpaidFound) {
                    firstUnpaidFound = true;
                } else {
                    // Disable bayar button untuk angsuran setelah yang pertama belum dibayar
                    const bayarButton = row.querySelector('button');
                    if (bayarButton) {
                        bayarButton.disabled = true;
                        bayarButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            }
        });
    }); 
    
    // Fungsi Bayar
    function bayarAngsuran(angsuranId, angsuranKe, memberId) {
        swal.fire({
            title: 'Konfirmasi',
            text: 'Bayar angsuran ke-' + angsuranKe + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                swal.showLoading();

                fetch(`/pinjaman/bayar-angsuran/${memberId}/${angsuranId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    // Pastikan respon dari server sukses (200 OK)
                    if (!res.ok) throw new Error('Gagal memproses ke server.');
                    return res.json();
                })
                .then(data => {
                    // Berhasil: Munculkan sukses lalu reload
                    window.location.href = `/pinjaman/struk-pinjaman/Angsuran/${data.data.no_transaksi_sp}/${angsuranId}`;
                    
                    // swal.fire('Berhasil!', data.message, 'success').then(() => {
                    //     location.reload();
                    // });
                })
                .catch(err => {
                    // Jika terjadi error (atau respon bukan JSON)
                    console.error(err);
                    swal.fire('Error', 'Gagal memproses pembayaran. Cek koneksi Anda.', 'error')
                    .then(() => location.reload());
                });
            }
        });
    }
</script>
@endsection