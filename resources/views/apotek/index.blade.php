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
            <div class="flex gap-4">
                <a href="{{ route('apotek.gudang') }}" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fas fa-warehouse mr-2"></i> Cek Stok Gudang
                </a>
                <a href="{{ route('apotek.resep') }}" class=" bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-white flex items-center">
                    <i class="fa-solid fa-book mr-2"></i> Orderan Masuk
                    <span id="count_order" class="ml-2  bg-white text-emerald-600 hidden px-2 py-1 rounded-4xl text-sm">0</span>
                </a>
                <span class="text-6xl">|</span>
                <div id="cart_obat" class="bg-emerald-600 hover:bg-emerald-700 px-8 py-4 rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest relative text-white flex items-center">
                    <i class="fa-solid fa-cart-shopping text-2xl "></i>
                    <span id="cart_count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </div>
            </div>
        </div>

        {{-- Keranjang Area --}}
        <div id="keranjang_area" class="fixed top-20 right-10 w-[35rem] bg-white/95 rounded-3xl shadow-2xl border border-emerald-100 overflow-hidden hidden z-50">
            <div class="bg-emerald-600 px-6 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-shopping-cart mr-2"></i> Keranjang Penjualan Obat</span>
                <i class="fa-solid fa-xmark cursor-pointer" id="close_cart"></i>
            </div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-4 border-b-2 border-emerald-100 pb-2 text-emerald-700 font-black uppercase text-sm tracking-widest">
                    <span>#</span>
                    Nama Obat
                    <span class="px-4 py-1 bg-emerald-100 text-emerald-700 rounded-xl text-xs border border-emerald-200 shadow-sm mx-2">
                        Jumlah
                    </span>
                    Total Harga 
                </div>
                {{-- kirim data ke controller --}}
                <form action="{{ route('apotek.proses-pembayaran-cart') }}" method="POST" id="form_pembayaran">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart_data">
                </form>
                <div id="area_cart" class="text-slate-600 font-bold italic text-center h-40 overflow-y-auto">
                    
                    <p class="font-bold italic text-center text-slate-600">Keranjang kosong.</p>
                </div>
                <hr class="mx-2">
                <div class="flex justify-between items-center mt-6">
                    <div class="p-4 bg-emerald-600 text-white rounded-2xl hover:bg-emerald-700 cursor-pointer shadow-xl transition-all active:scale-95 flex items-center gap-2" onclick="prosesPembayaran()">
                        <i class="fas fa-cash-register"></i> 
                        <span class="font-black uppercase text-sm">Checkout</span>
                    </div>
                    <span id="total_nominal_cart" class="block text-center text-2xl mt-2 font-bold text-emerald-700">Rp. 0</span>
                </div>
            </div>
        </div>

        {{-- Container Tabel Hijau --}}
        <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-emerald-100">
            <div class="bg-emerald-600 px-8 py-4 text-white font-black uppercase tracking-widest flex justify-between items-center">
                <span><i class="fas fa-pills mr-2"></i> Daftar Obat Siap Jual</span>
                <span class="bg-white text-emerald-600 px-4 py-1 rounded-full text-xs shadow-inner font-black">
                    Tersedia: {{ $obats->count() }} Macam Obat
                </span>
            </div>
            
            <div class="p-8">
                <table class="w-full text-left">
                    <thead class="text-emerald-700 border-b-2 border-emerald-100 font-black uppercase text-sm tracking-widest">
                        <tr>
                            <th class="py-4">KODE</th>
                            <th class="py-4">NAMA OBAT</th>
                            <th class="py-4">HARGA JUAL</th>
                            <th class="py-4 text-center">STOK RETAIL</th>
                            <th class="py-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50 font-bold">
                        @forelse($obats as $o)
                        <tr class="hover:bg-emerald-50/50 transition-colors text-slate-700 group">
                            <td class="py-4 font-mono text-emerald-600">{{ $o->kode_obat }}</td>
                            <td class="py-4 uppercase tracking-tighter">{{ $o->nama_obat }}</td>
                            <td class="py-4 text-slate-800">Rp {{ number_format($o->harga_jual, 0, ',', '.') }}</td>
                            <td class="py-4 text-center">
                                <span class="px-4 py-1 bg-emerald-100 text-emerald-700 rounded-xl text-xs border border-emerald-200 shadow-sm">
                                    {{ $o->stok_apotek }} {{ $o->satuan }}
                                </span>
                            </td>
                            <td class="py-4 text-center flex justify-center items-center gap-4">
                                {{-- Tombol Aktif Jual Obat Membuka Modal --}}
                                <button onclick="openJualModal({{ json_encode($o) }})" class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase shadow-md hover:bg-emerald-700 transition-all hover:scale-105 active:scale-95">
                                    <i class="fas fa-shopping-cart mr-1"></i> Jual Satuan
                                </button>
                                | 
                                {{-- tombol keranjang --}}
                                <i class="fa-solid fa-cart-plus cursor-pointer text-2xl hover:text-emerald-700 hover:scale-110 transition-all duration-500 text-white bg-emerald-600 p-2 " id="add_item_{{ $o->id }}" onclick="addToCart({{ json_encode($o) }})"></i>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center text-slate-400 font-bold italic text-xl tracking-wide">
                                <i class="fas fa-box-open text-6xl mb-4 block opacity-20"></i>
                                Stok di Apotek kosong.<br>
                                <span class="text-xs uppercase not-italic text-emerald-600 font-black">Silakan lakukan mutasi stok dari gudang apotek</span>
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
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-emerald-100 animate-in fade-in zoom-in duration-300">
        <div class="bg-emerald-600 p-6 text-white text-center">
            <h3 class="text-xl font-black uppercase tracking-widest"><i class="fas fa-cash-register mr-2"></i> Transaksi Jual</h3>
        </div>
        
        <form id="formJualObat" method="POST" class="p-8 space-y-6">
            @csrf
            <div class="text-center space-y-1">
                <h2 id="displayNamaObat" class="text-2xl font-black text-slate-800 uppercase tracking-tighter"></h2>
                <p id="displayHargaObat" class="text-emerald-600 font-bold italic"></p>
            </div>

            <div class="bg-emerald-50 p-6 rounded-2xl border-2 border-emerald-100">
                <label class="text-[10px] font-black text-emerald-600 uppercase block mb-2 text-center">Jumlah Pembelian</label>
                <div class="flex items-center justify-center gap-4">
                    <input type="number" name="qty" id="inputQty" required min="1" 
                        class="w-full bg-transparent border-b-4 border-emerald-200 text-center text-4xl font-black text-slate-800 outline-none focus:border-emerald-600 transition-all" 
                        value="1" oninput="hitungTotal()">
                </div>
                <p id="displayStokTersedia" class="text-[10px] text-center mt-3 text-slate-400 font-bold uppercase"></p>
            </div>

            <div class="border-t border-dashed border-emerald-200 pt-4 flex justify-between items-center">
                <span class="text-xs font-black text-slate-400 uppercase">Total Bayar</span>
                <span id="displayTotal" class="text-2xl font-black text-emerald-600">Rp 0</span>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase shadow-xl hover:bg-emerald-700 transition-all active:scale-95">
                    Konfirmasi Penjualan
                </button>
                <button type="button" onclick="closeModal()" class="text-slate-400 font-black uppercase text-[10px] tracking-widest hover:text-slate-600 transition-all">
                    Batal / Kembali
                </button>
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<script>
    let currentObat = null;
    let resepMasuks = @json($resepMasuks);
    let cart = [];

    function openJualModal(obat) {
        currentObat = obat;
        document.getElementById('modalJualObat').classList.remove('hidden');
        document.getElementById('displayNamaObat').innerText = obat.nama_obat;
        document.getElementById('displayHargaObat').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(obat.harga_jual) + ' / ' + obat.satuan;
        document.getElementById('displayStokTersedia').innerText = 'Stok Tersedia: ' + obat.stok_apotek + ' ' + obat.satuan;
        document.getElementById('inputQty').max = obat.stok_apotek;
        document.getElementById('inputQty').value = 1;
        
        // Set Action Form Dinamis
        document.getElementById('formJualObat').action = `/apotek/proses-jual/${obat.id}`;
        
        hitungTotal();
    }

    if(resepMasuks.length > 0) {
        document.getElementById('count_order').classList.remove('hidden');
        document.getElementById('count_order').innerText = resepMasuks.length;
    }else{
        document.getElementById('count_order').classList.add('hidden');
    }

    function hitungTotal() {
        const qty = document.getElementById('inputQty').value;
        const total = qty * currentObat.harga_jual;
        document.getElementById('displayTotal').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function closeModal() {
        document.getElementById('modalJualObat').classList.add('hidden');
    }

    document.getElementById('cart_obat').addEventListener('click', function() {
        const keranjangArea = document.getElementById('keranjang_area');
        if (keranjangArea.classList.contains('hidden')) {
            keranjangArea.classList.remove('hidden');
        } else {
            keranjangArea.classList.add('hidden');
        }
    });


    document.getElementById('close_cart').addEventListener('click', function() {
        document.getElementById('keranjang_area').classList.add('hidden');
    });

    // fungsi penanda keranjang tertambah

    function updateCartDisplay() {
        const cartCount = document.getElementById('cart_count');
        const areaCart = document.getElementById('area_cart');

        cartCount.innerText = cart.length;

        if (cart.length === 0) {
            areaCart.innerHTML = '<p class="font-bold italic text-center text-slate-600">Keranjang kosong.</p>';
            return;
        }

        let cartHTML = '<div class="space-y-4">';
        cart.forEach(item => {
            cartHTML += `
                <div class="flex justify-between items-center">
                    <i class="fa-solid fa-capsules"></i>
                    <span class="font-bold">${item.nama_obat}</span>
                    <div class="">
                        <span class="text-emerald-600 cursor-pointer font-black text-xl" id="item_decrement_${item.id}"> - </span>
                        <span class="mx-2">${item.qty}</span>
                        <span class="text-emerald-600 cursor-pointer font-black text-xl" id="item_increment_${item.id}"> + </span>
                    </div>
                    <span class="text-emerald-600 font-black">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_jual * item.qty)}</span>
                </div>
                <hr class="my-2 border-emerald-100/50">
            `;
        });
        cartHTML += '</div>';

        areaCart.innerHTML = cartHTML;
    }

    function prosesPembayaran() {
        if (cart.length === 0) {
            swal.fire('Keranjang Kosong', 'Silakan tambahkan obat ke keranjang sebelum melakukan pembayaran.', 'warning');
            return;
        }

        // Kirim data cart ke form tersembunyi
        document.getElementById('cart_data').value = JSON.stringify(cart);
        document.getElementById('form_pembayaran').submit();
    }


    function updateTotalNominal() {
        const totalNominalCart = document.getElementById('total_nominal_cart');
        const total = cart.reduce((sum, item) => sum + (item.harga_jual * item.qty), 0);
        totalNominalCart.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function addToCart(obat) {
        const existingItem = cart.find(item => item.id === obat.id);
        if (existingItem) {
            if (existingItem.qty < obat.stok_apotek) {
                existingItem.qty += 1;
            } else {
                swal.fire('Stok Habis', 'Stok obat tidak mencukupi untuk menambah jumlah di keranjang.', 'warning');
            }
        } else {
            cart.push({ ...obat, qty: 1 });
        }
        updateCartDisplay();
        updateTotalNominal();

        document.getElementById('cart_obat').classList.add('scale-110', 'animate-bounce');
        setTimeout(() => {
            document.getElementById('cart_obat').classList.remove('scale-110', 'animate-bounce');
        }, 500);
    }

    document.getElementById('area_cart').addEventListener('click', function(event) {
        if (event.target.id.startsWith('item_increment_')) {
            const itemId = parseInt(event.target.id.replace('item_increment_', ''));
            const item = cart.find(i => i.id === itemId);
            if (item && item.qty < item.stok_apotek) {
                item.qty += 1;
                updateCartDisplay();
            } else {
                swal.fire('Stok Habis', 'Stok obat tidak mencukupi untuk menambah jumlah di keranjang.', 'warning');
            }
        } else if (event.target.id.startsWith('item_decrement_')) {
            const itemId = parseInt(event.target.id.replace('item_decrement_', ''));
            const itemIndex = cart.findIndex(i => i.id === itemId);
            if (itemIndex > -1) {
                if (cart[itemIndex].qty > 1) {
                    cart[itemIndex].qty -= 1;
                } else {
                    cart.splice(itemIndex, 1);
                }
                updateCartDisplay();
                updateTotalNominal();
            }
        }
    });


    if({{ session('success') ? 'true' : 'false' }}) 
    {
        swal.fire('Sukses', '{{ session('success') }}', 'success');
    }
    else if({{ session('error') ? 'true' : 'false' }}) 
    {
        swal.fire('Gagal', '{{ session('error') }}', 'error');
    }
</script>
@endsection