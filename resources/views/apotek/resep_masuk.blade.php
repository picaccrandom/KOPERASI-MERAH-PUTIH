@extends('layouts.master')

@section('content')
    {{-- Background menggunakan asset apotek dengan overlay Hijau Emerald --}}
    <div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-apotek.png') }}');">
        <div class="bg-emerald-900/40 backdrop-blur-md min-h-screen p-10">

            {{-- Header Pelayanan Apotek --}}
            <div class="flex justify-between items-end mb-10 border-l-8 border-emerald-500 pl-6 text-white">
                <div>
                    <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-100">
                        Pelayanan <span class="bg-emerald-600 px-3 rounded-lg shadow-lg text-white">Apotek</span>
                    </h1>
                    <p class="text-emerald-100 font-bold mt-2 italic">Dashboard Resep Obat Desa Nangsri</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('apotek.gudang') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                        <i class="fas fa-warehouse mr-2"></i> Cek Stok Gudang
                    </a>
                    <a href="{{ route('apotek.index') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                        <i class="fa-solid fa-book mr-2"></i> Jual Obat
                    </a>
                </div>
            </div>

            {{-- Container Tabel Hijau --}}
            <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
                <div
                    class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                    <span><i class="fas fa-pills mr-2"></i> Resep Obat Masuk</span>
                    <span class="bg-white text-emerald-600 px-4 py-1 rounded-full text-xs shadow-inner font-black">
                        Tersedia: {{ $resepMasuks->count() }} Orderan Obat
                    </span>
                </div>

                <div class="p-8">
                    <table class="w-full text-left">
                        <thead
                            class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest text-center">
                            <tr>
                                <th class="py-4">PENDAFTARAN ID</th>
                                <th class="py-4">Nama Pasien</th>
                                <th class="py-4">Catatan</th>
                                <th class="py-4">TOTAL HARGA</th>
                                <th class="py-4 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50 font-bold text-center">
                            @forelse($resepMasuks as $r)
                                <tr class="hover:bg-emerald-50/50 transition-colors text-slate-700 group">
                                    <td class="py-4 font-mono text-emerald-600">{{ $r->pendaftaran_klinik_id }}</td>
                                    <td class="py-4 uppercase tracking-tighter">{{ $r->member->nama_lengkap ?? 'Tanpa Nama' }}</td>
                                    <td class="py-4 flex justify-center">
                                        <ul class="space-y-1">
                                            @php
                                                // Decode JSON dengan proteksi agar tidak error Undefined Array Key
                                                $orderObat = json_decode($r->order_body, true) ?? [];
                                            @endphp
                                            @foreach ($orderObat as $item)
                                                <li class="text-xl grid grid-cols-2 w-72 gap-2 text-left">
                                                    {{-- Gunakan Null Coalescing (??) agar jika index 1 tidak ada, sistem tidak crash --}}
                                                    <span class="font-black text-sm col-span-1">
                                                        {{ $item[1] ?? ($item['nama_obat'] ?? 'Obat') }}
                                                    </span>
                                                    <span class="font-black text-sm col-span-1 text-right">
                                                        ({{ $item[2] ?? ($item['qty'] ?? 0) }})
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="py-4 uppercase tracking-tighter text-lg text-red-400 font-black">
                                        Rp. {{ number_format($r->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-center">
                                        {{-- Tombol untuk proses resep --}}
                                        <button onclick="openJualModal({{ $r->pendaftaranKlinik->rekamMedis->id ?? 0 }})"
                                            class="text-emerald-600 px-6 py-2 rounded-2xl overflow-hidden hover:scale-110 transition-all active:scale-95"
                                            title="Proses Resep">
                                            <i class="fa-solid fa-file-prescription text-2xl"></i>
                                        </button>
                                        
                                        <form action="{{ route('apotek.hapusResep', $r->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus resep ini?')"
                                                class="text-red-600 px-6 py-2 rounded-2xl overflow-hidden hover:scale-110 transition-all active:scale-95"
                                                title="Hapus Orderan">
                                                <i class="fas fa-trash-alt text-2xl"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                        <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                        Tidak ada resep obat masuk.<br>
                                        <span class="text-xs uppercase not-italic text-emerald-600 font-black">Silakan tunggu pesanan dari klinik</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TRANSAKSI PENJUALAN --}}
    <div id="modalJualObat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl overflow-hidden">
            <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-widest flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-3 text-2xl"></i> Proses Resep Pasien
                    </h3>
                    <div class="mt-2 text-sm opacity-90 font-bold">
                        PASIEN: <span id="nama_pasien">-</span> | NO. REG: <span id="no_reg">-</span>
                    </div>
                </div>
                <button onclick="closeModal()" class="text-white hover:text-red-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('apotek.bayarOrder') }}" class="p-8">
                @csrf
                <input type="hidden" name="member_id" id="input_member_id">
                <input type="hidden" name="pendaftaran_klinik_id" id="input_pendaftaran_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Info Medis --}}
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                            <label class="block text-xs font-black text-blue-600 uppercase mb-1">Diagnosa Dokter</label>
                            <textarea id="diagnosa" readonly class="w-full bg-transparent font-bold text-slate-700 outline-none resize-none" rows="3"></textarea>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                            <label class="block text-xs font-black text-emerald-600 uppercase mb-1">Terapi / Tindakan</label>
                            <textarea id="tindakan" readonly class="w-full bg-transparent font-bold text-slate-700 outline-none resize-none" rows="3"></textarea>
                        </div>
                    </div>

                    {{-- Area Resep --}}
                    <div class="flex flex-col">
                        <label class="text-sm font-black text-slate-700 mb-4 uppercase">Item Obat & Qty</label>
                        <div id="obatArea" class="space-y-4 max-h-64 overflow-y-auto pr-2">
                            {{-- Dinamis via JS --}}
                        </div>
                        <button type="button" onclick="tambahAreaObat()" class="mt-4 text-blue-600 font-bold text-sm hover:underline">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Obat Lain
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-10 border-t pt-6">
                    <div class="text-2xl font-black text-slate-800 uppercase">
                        Total: <span class="text-emerald-600">Rp. <span id="totalHarga">0</span></span>
                    </div>
                    <div class="flex gap-4">
                        <button type="button" onclick="closeModal()" class="px-8 py-3 bg-slate-200 text-slate-700 rounded-xl font-black uppercase">Batal</button>
                        <button type="submit" class="px-10 py-3 bg-emerald-600 text-white rounded-xl font-black uppercase shadow-lg hover:bg-emerald-700 transition-all">
                            Bayar & Selesaikan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const obats = @json($obats);
        const rekamMedis = @json($rekamMedis);
        const resepMasuk = @json($resepMasuks);

        function openJualModal(rekamMedisId) {
            const data = rekamMedis.find(r => r.id === rekamMedisId);
            if (!data) return;

            document.getElementById('modalJualObat').classList.remove('hidden');
            document.getElementById('nama_pasien').innerText = data.pendaftaran_klinik.member.nama_lengkap;
            document.getElementById('no_reg').innerText = data.pendaftaran_klinik.no_registrasi;
            document.getElementById('diagnosa').value = data.diagnosa;
            document.getElementById('tindakan').value = data.tindakan;
            document.getElementById('input_member_id').value = data.pendaftaran_klinik.member_id;
            document.getElementById('input_pendaftaran_id').value = data.pendaftaran_klinik.id;

            const obatArea = document.getElementById('obatArea');
            obatArea.innerHTML = '';

            const resep = resepMasuk.find(r => r.pendaftaran_klinik_id === data.pendaftaran_klinik.id);
            if (resep) {
                const items = JSON.parse(resep.order_body);
                items.forEach(item => {
                    // Mendeteksi apakah item menggunakan index [0] (kode) atau [1] (nama)
                    const kodeObat = item[0] ?? item.kode_obat;
                    const qty = item[2] ?? item.qty;
                    tambahAreaObat(kodeObat, qty);
                });
            }
            hitungTotalHarga();
        }

        function tambahAreaObat(kode = '', qty = 1) {
            const obatArea = document.getElementById('obatArea');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200';
            
            let options = '<option value="">Pilih Obat...</option>';
            obats.forEach(o => {
                const selected = o.kode_obat == kode ? 'selected' : '';
                options += `<option value="${o.kode_obat}" ${selected}>${o.nama_obat} (Stok: ${o.stok_apotek})</option>`;
            });

            div.innerHTML = `
                <select name="resep_obat[]" onchange="hitungTotalHarga()" class="flex-1 p-2 rounded-lg border-none font-bold text-sm bg-transparent outline-none">
                    ${options}
                </select>
                <input type="number" name="qty[]" value="${qty}" oninput="hitungTotalHarga()" class="w-20 p-2 rounded-lg border-none font-bold text-center bg-white shadow-sm" min="1">
                <button type="button" onclick="this.parentElement.remove(); hitungTotalHarga();" class="text-red-500 px-2">
                    <i class="fas fa-times-circle"></i>
                </button>
            `;
            obatArea.appendChild(div);
            hitungTotalHarga();
        }

        function hitungTotalHarga() {
            let total = 0;
            const selects = document.querySelectorAll('select[name="resep_obat[]"]');
            const qtys = document.querySelectorAll('input[name="qty[]"]');

            selects.forEach((select, i) => {
                const obat = obats.find(o => o.kode_obat == select.value);
                if (obat) {
                    total += obat.harga_jual * (qtys[i].value || 0);
                }
            });

            document.getElementById('totalHarga').innerText = new Intl.NumberFormat('id-ID').format(total);
        }

        function closeModal() {
            document.getElementById('modalJualObat').classList.add('hidden');
        }
    </script>
@endsection