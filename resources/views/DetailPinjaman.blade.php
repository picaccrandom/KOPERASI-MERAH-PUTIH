@extends('layouts.master')
@section('title', 'Detail Pinjaman')

@section('content')
    <div class="relative bg-white/40 backdrop-blur-2xl rounded-lg mx-32 p-16">
        <div class="border-l-8 border-l-green-400 pl-4 mb-8">
            <div class="border-b-2 pb-2 mb-2 border-b-slate-500 inline-block text-4xl font-bold text-white uppercase shadow-sm">
                Detail <span class="px-1 bg-black text-white rounded-md shadow-md">Pinjaman & Angsuran</span>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg px-14 py-8">
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Rincian Angsuran</h4>
                        <button id="btn-rincian-angsuran" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">Buka Rincian Angsuran</button>
                    </div>

                    <div class="overflow-x-auto hidden max-h-64 relative border rounded" id="rincian-angsuran">
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
            <a href="{{ route('pinjaman.index') }}" class="text-decoration-none mt-4 inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">Kembali</a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Toggle Rincian
    document.getElementById('btn-rincian-angsuran').addEventListener('click', function() {
        var rincianAngsuran = document.getElementById('rincian-angsuran');
        if (rincianAngsuran.classList.contains('hidden')) {
            rincianAngsuran.classList.remove('hidden');
            this.textContent = 'Tutup Rincian Angsuran';
        } else {
            rincianAngsuran.classList.add('hidden');
            this.textContent = 'Buka Rincian Angsuran';
        }
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
                    swal.fire('Berhasil!', data.message, 'success').then(() => {
                        location.reload();
                    });
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