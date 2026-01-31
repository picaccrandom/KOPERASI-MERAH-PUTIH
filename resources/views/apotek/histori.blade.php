@extends('layouts.master')
@section('content')
<div class="p-10 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-black text-slate-800 tracking-tighter">Histori Penjualan Apotek</h1>
        <a href="{{ route('apotek.index') }}" class="bg-slate-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-slate-800 transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kasir
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-blue-600 text-white uppercase text-xs font-black tracking-widest">
                    <th class="px-8 py-5">Tanggal</th>
                    <th class="px-8 py-5">No. Invoice</th>
                    <th class="px-8 py-5">Nama Pasien/Pembeli</th>
                    <th class="px-8 py-5">Total Bayar</th>
                    <th class="px-8 py-5">Status</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 font-bold">
                @forelse($histori as $h)
                <tr class="hover:bg-blue-50/50 transition-colors border-b border-slate-50">
                    <td class="px-8 py-6 text-sm text-slate-500">{{ $h->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-8 py-6">
                        <button onclick="showDetail('{{ $h->kode_transaksi }}')" class="text-blue-600 hover:underline decoration-2 underline-offset-4">
                            {{ $h->kode_transaksi }}
                        </button>
                    </td>
                    <td class="px-8 py-6 uppercase tracking-tighter">{{ $h->nama ?? ($h->member->nama_lengkap ?? 'Umum') }}</td>
                    <td class="px-8 py-6 text-lg font-black text-slate-800">Rp {{ number_format($h->Nominal, 0, ',', '.') }}</td>
                    <td class="px-8 py-6">
                        <span class="px-4 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-200">LUNAS</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center text-slate-400 italic">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6 bg-slate-50">
            {{ $histori->links() }}
        </div>
    </div>
</div>

{{-- MODAL DETAIL (POP UP) --}}
<div id="modalDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-300">
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <h3 class="text-xl font-black uppercase tracking-widest"><i class="fas fa-receipt mr-2"></i> Detail Obat Terjual</h3>
            <button onclick="closeModal()" class="text-white hover:rotate-90 transition-all"><i class="fas fa-times text-2xl"></i></button>
        </div>
        <div class="p-8">
            <div class="mb-4 text-sm font-bold text-slate-400 uppercase tracking-widest">No. Invoice: <span id="modal_invoice" class="text-blue-600"></span></div>
            <table class="w-full text-left">
                <thead class="border-b-2 border-slate-100 text-[10px] font-black uppercase text-slate-400">
                    <tr>
                        <th class="py-3">Nama Obat</th>
                        <th class="py-3 text-center">Qty</th>
                        <th class="py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="detail_body" class="text-slate-700 font-bold">
                    {{-- Isi detail dari AJAX --}}
                </tbody>
            </table>
            <div class="mt-8 flex justify-end">
                <button onclick="closeModal()" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-black uppercase hover:bg-slate-200 transition-all">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDetail(kode) {
        document.getElementById('modal_invoice').innerText = kode;
        document.getElementById('detail_body').innerHTML = '<tr><td colspan="3" class="py-10 text-center text-slate-400 animate-pulse uppercase font-black text-xs">Mengambil data...</td></tr>';
        document.getElementById('modalDetail').classList.remove('hidden');

        // Tarik data pakai fetch/AJAX
        fetch(`/apotek/histori/${kode}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(item => {
                    html += `
                        <tr class="border-b border-slate-50">
                            <td class="py-4 uppercase text-sm">${item.nama_obat}</td>
                            <td class="py-4 text-center">${item.qty}</td>
                            <td class="py-4 text-right">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                        </tr>
                    `;
                });
                document.getElementById('detail_body').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('detail_body').innerHTML = '<tr><td colspan="3" class="py-10 text-center text-red-500">Gagal mengambil data.</td></tr>';
            });
    }

    function closeModal() {
        document.getElementById('modalDetail').classList.add('hidden');
    }
</script>
@endsection