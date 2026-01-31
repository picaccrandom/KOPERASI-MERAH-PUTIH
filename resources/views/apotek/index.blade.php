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
                <p class="text-emerald-100 font-bold mt-2 italic">Dashboard Penjualan Obat Desa Nangsri</p>
            </div>
            <div class="flex gap-4 items-center">
                {{-- TOMBOL HISTORI PENJUALAN (BARU) --}}
                <a href="{{ route('apotek.histori') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fas fa-history mr-2"></i> Histori
                </a>

                <a href="{{ route('apotek.gudang') }}" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fas fa-warehouse mr-2"></i> Gudang
                </a>
                
                <a href="{{ route('apotek.resep') }}" class=" bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center relative">
                    <i class="fa-solid fa-book mr-2"></i> Order Masuk
                    <span id="count_order" class="absolute -top-2 -right-2 bg-red-500 text-white hidden px-2 py-1 rounded-full text-xs font-bold shadow-lg">0</span>
                </a>

                <div id="cart_obat" class="bg-white/20 hover:bg-white/30 px-6 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest relative text-white flex items-center cursor-pointer border border-white/30">
                    <i class="fa-solid fa-cart-shopping text-2xl"></i>
                    <span id="cart_count" class="absolute -top-2 -right-2 bg-yellow-400 text-emerald-900 rounded-full w-6 h-6 flex items-center justify-center text-xs font-black shadow-lg">0</span>
                </div>
            </div>
        </div>

        {{-- Keranjang Area --}}
        <div id="keranjang_area" class="fixed top-24 right-10 w-[30rem] bg-white rounded-3xl shadow-2xl border border-emerald-100 overflow-hidden hidden z-50 animate-in slide-in-from-right duration-300">
            <div class="bg-emerald-600 px-6 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-shopping-cart mr-2"></i> Keranjang</span>
                <i class="fa-solid fa-xmark cursor-pointer text-xl hover:rotate-90 transition-all" id="close_cart"></i>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2 text-emerald-700 font-black uppercase text-[10px] tracking-widest">
                    <span class="w-1/2">Nama Obat</span>
                    <span class="w-1/4 text-center">Qty</span>
                    <span class="w-1/4 text-right">Total</span>
                </div>
                
                <form action="{{ route('apotek.proses-pembayaran-cart') }}" method="POST" id="form_pembayaran">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart_data">
                </form>

                <div id="area_cart" class="text-slate-600 font-bold space-y-3 max-h-60 overflow-y-auto pr-2">
                    <p class="font-bold italic text-center text-slate-400 py-10">Keranjang kosong.</p>
                </div>

                <div class="mt-6 pt-4 border-t-2 border-dashed border-slate-100 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase">Total Bayar</p>
                        <span id="total_nominal_cart" class="text-2xl font-black text-emerald-700">Rp. 0</span>
                    </div>
                    <button onclick="prosesPembayaran()" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-black uppercase shadow-lg hover:bg-emerald-700 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Checkout
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel Obat --}}
        <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
            <div class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-pills mr-2"></i> Stok Siap Jual</span>
                <span class="bg-white/20 text-white px-4 py-1 rounded-full text-xs font-bold border border-white/30">
                    {{ $obats->count() }} Produk
                </span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest">
                        <tr>
                            <th class="py-4">Kode</th>
                            <th class="py-4">Nama Obat</th>
                            <th class="py-4">Harga Jual</th>
                            <th class="py-4 text-center">Stok</th>
                            <th class="py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50 font-bold">
                        @forelse($obats as $o)
                        <tr class="hover:bg-emerald-50/50 transition-colors text-slate-700 group">
                            <td class="py-4 font-mono text-emerald-600">{{ $o->kode_obat }}</td>
                            <td class="py-4 uppercase">{{ $o->nama_obat }}</td>
                            <td class="py-4">Rp {{ number_format($o->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-4 text-center">
                                <span class="px-4 py-1 {{ $o->stok_apotek < 10 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' }} rounded-xl text-xs border shadow-sm">
                                    {{ $o->stok_apotek }} {{ $o->satuan }}
                                </span>
                            </td>
                            <td class="py-4 text-center flex justify-center items-center gap-3">
                                <button onclick="openJualModal({{ json_encode($o) }})" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase shadow-md hover:bg-blue-700 transition-all">
                                    <i class="fas fa-cash-register mr-1"></i> Jual
                                </button>
                                <button onclick="addToCart({{ json_encode($o) }})" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase shadow-md hover:bg-emerald-700 transition-all">
                                    <i class="fas fa-cart-plus mr-1"></i> + Keranjang
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                Stok Kosong
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL JUAL SATUAN --}}
<div id="modalJualObat" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-300">
        <div class="bg-blue-600 p-6 text-white text-center">
            <h3 class="text-xl font-black uppercase tracking-widest">Transaksi Cepat</h3>
        </div>
        <form id="formJualObat" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="text-center">
                <h2 id="displayNamaObat" class="text-2xl font-black text-slate-800 uppercase"></h2>
                <p id="displayHargaObat" class="text-blue-600 font-bold"></p>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100 text-center">
                <label class="text-[10px] font-black text-slate-400 uppercase block mb-2">Jumlah Beli</label>
                <input type="number" name="qty" id="inputQty" required min="1" class="w-full bg-transparent text-center text-4xl font-black text-slate-800 outline-none" value="1" oninput="hitungTotal()">
                <p id="displayStokTersedia" class="text-[10px] mt-2 text-slate-400 font-bold uppercase"></p>
            </div>
            <div class="flex justify-between items-center border-t border-dashed pt-4">
                <span class="text-xs font-black text-slate-400 uppercase">Total</span>
                <span id="displayTotal" class="text-2xl font-black text-blue-600">Rp 0</span>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase shadow-lg hover:bg-blue-700 transition-all">Simpan Transaksi</button>
            <button type="button" onclick="closeModal()" class="w-full text-slate-400 font-black uppercase text-[10px] tracking-widest">Batal</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentObat = null;
    let cart = [];
    let resepMasuks = @json($resepMasuks);

    // Initial Order Count
    if(resepMasuks.length > 0) {
        document.getElementById('count_order').classList.remove('hidden');
        document.getElementById('count_order').innerText = resepMasuks.length;
    }

    function openJualModal(obat) {
        currentObat = obat;
        document.getElementById('modalJualObat').classList.remove('hidden');
        document.getElementById('displayNamaObat').innerText = obat.nama_obat;
        document.getElementById('displayHargaObat').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(obat.harga_jual);
        document.getElementById('displayStokTersedia').innerText = 'Stok: ' + obat.stok_apotek;
        document.getElementById('inputQty').max = obat.stok_apotek;
        document.getElementById('inputQty').value = 1;
        document.getElementById('formJualObat').action = `/apotek/proses-jual/${obat.id}`;
        hitungTotal();
    }

    function hitungTotal() {
        const qty = document.getElementById('inputQty').value || 0;
        const total = qty * currentObat.harga_jual;
        document.getElementById('displayTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function closeModal() {
        document.getElementById('modalJualObat').classList.add('hidden');
    }

    // CART LOGIC
    document.getElementById('cart_obat').addEventListener('click', () => {
        document.getElementById('keranjang_area').classList.toggle('hidden');
    });

    document.getElementById('close_cart').addEventListener('click', () => {
        document.getElementById('keranjang_area').classList.add('hidden');
    });

    function addToCart(obat) {
        const exist = cart.find(i => i.id === obat.id);
        if (exist) {
            if (exist.qty < obat.stok_apotek) exist.qty += 1;
            else Swal.fire('Stok Terbatas', 'Jumlah melebihi stok tersedia.', 'warning');
        } else {
            cart.push({ ...obat, qty: 1 });
        }
        renderCart();
    }

    function renderCart() {
        const area = document.getElementById('area_cart');
        const count = document.getElementById('cart_count');
        const totalDisp = document.getElementById('total_nominal_cart');
        
        count.innerText = cart.length;
        
        if (cart.length === 0) {
            area.innerHTML = '<p class="font-bold italic text-center text-slate-400 py-10">Keranjang kosong.</p>';
            totalDisp.innerText = 'Rp. 0';
            return;
        }

        let html = '';
        let total = 0;
        cart.forEach((item, index) => {
            const sub = item.harga_jual * item.qty;
            total += sub;
            html += `
                <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="w-1/2">
                        <p class="text-xs font-black uppercase text-slate-800 truncate">${item.nama_obat}</p>
                        <p class="text-[10px] text-emerald-600 font-bold">@ Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual)}</p>
                    </div>
                    <div class="w-1/4 flex items-center justify-center gap-2">
                        <button onclick="updateQty(${index}, -1)" class="text-emerald-600 hover:text-red-500"><i class="fas fa-minus-circle"></i></button>
                        <span class="text-sm font-black">${item.qty}</span>
                        <button onclick="updateQty(${index}, 1)" class="text-emerald-600"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <div class="w-1/4 text-right">
                        <p class="text-xs font-black text-slate-800">Rp ${new Intl.NumberFormat('id-ID').format(sub)}</p>
                    </div>
                </div>
            `;
        });
        area.innerHTML = html;
        totalDisp.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function updateQty(index, change) {
        cart[index].qty += change;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        else if (cart[index].qty > cart[index].stok_apotek) {
            cart[index].qty = cart[index].stok_apotek;
            Swal.fire('Stok Maksimal', 'Stok tidak mencukupi.', 'info');
        }
        renderCart();
    }

    function prosesPembayaran() {
        if (cart.length === 0) return Swal.fire('Peringatan', 'Keranjang masih kosong.', 'info');
        document.getElementById('cart_data').value = JSON.stringify(cart);
        document.getElementById('form_pembayaran').submit();
    }

    // Alerts
    @if(session('success')) Swal.fire('Berhasil', '{{ session('success') }}', 'success'); @endif
    @if(session('error')) Swal.fire('Error', '{{ session('error') }}', 'error'); @endif
</script>
@endsection