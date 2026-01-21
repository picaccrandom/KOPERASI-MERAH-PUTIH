@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i> KERANJANG BELANJA
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">CARI BARANG (KODE/NAMA)</label>
                            <input type="text" id="search-barang" class="form-control" placeholder="Ketik untuk mencari barang...">
                            <div class="dropdown-menu w-[97%] text-base" id="dropdown-barang">s</div>
                        </div>
                    </div>
                    
                    <div class="table-responsiv lg:h-[29rem] overflow-y-auto">
                        <table class="table table-bordered align-middle" id="table-keranjang">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="150">Harga</th>
                                    <th width="100">Qty</th>
                                    <th width="150">Subtotal</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="fas fa-money-bill-wave me-2"></i> PEMBAYARAN
                </div>
                <div class="card-body">
                    <form id="form-transaksi">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="member_id" id="member_id" value="">
                        <input type="hidden" name="total_harga" id="total_harga" value="0">
                        <input type="hidden" name="kategori" id="kategori" value="reguler">
                        <div class="mb-3 space-y-4">
                            <label class="form-label fw-bold small uppercase">Pilih Anggota (Default: Non-Member)</label>
                            <div class="mb-2 flex justify-evenly">
                                <label class="flex justify-center items-center cursor-pointer">
                                    <input type="radio" name="kategori" id="kategori_yes" value="member" class="sr-only peer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="ml-2 bi bi-person-circle opacity-40 peer-checked:opacity-100  peer-checked:text-orange-600 text-green-600" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                    </svg>
                                    <span class="uppercase font-semibold text-slate-400">Member</span>
                                </label>
                                <label class="flex-row justify-center items-center cursor-pointer gap-y-2">
                                    <input type="radio" name="kategori" id="kategori_no" value="reguler" class="sr-only peer" checked>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="ml-2   bi bi-person-circle opacity-40 peer-checked:opacity-100  peer-checked:text-orange-600 text-slate-500" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                    </svg>
                                    <span class="uppercase font-semibold">Reguler</span>
                                </label>
                            </div>
                        </div>

                        <div class="nik-form mb-3 space-y-4 hidden">
                        <!-- NIK Input -->
                            <div>
                                <label class="block text-sm  font-semibold text-black mb-1 uppercase">NIK Member</label>
                                <div class="flex gap-2">
                                    <input type="text" id="nik-input" placeholder="Masukkan NIK untuk diskon" 
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                <p id="member-info" class="text-xs text-black mt-1 hidden"></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">METODE BAYAR</label>
                            <select name="metode_bayar" id="metode_bayar" class="form-select border-dark fw-bold text-primary">
                                <option value="tunai">TUNAI (CASH)</option>
                                <option value="bon">BON (KHUSUS ANGGOTA)</option>
                            </select>
                        </div>
                        <div class="space-y-2 my-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total</span>
                                <span id="total-kotor" class="font-medium">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 relative">Diskon Anggota <span class="text-white align-middle text-xs ml-1 bg-green-600 py-0.5 px-1 w-23 rounded-xs">Discount (10%)</span></span>
                                <span id="discount" class="font-medium text-green-600">Rp 0</span>
                            </div>
                        </div>
                        
                        <div class="bg-light p-3 rounded mb-1 text-center">
                            <h6 class="fw-bold text-muted mb-1">TOTAL BELANJA</h6>
                            <h2 class="fw-bold text-danger" id="display-total">Rp 0</h2>
                        </div>

                        <hr class="my-2">
                        <div class="mb-2 flex gap-3">
                            <div>
                                <label for="nominal" id="nominal-label" class="text-xs ml-6">Nominal (Tunai)</label>
                                <div class="flex items-center">
                                    <span class="text-xs align-middle font-bold mr-1">Rp. </span>
                                    <input type="number" name="nominal" id="nominal" min="0" class="w-[90%] px-2 py-1 rounded-lg border border-dark font-bold text-primary focus:outline-2 focus:outline-green-500" placeholder="0">
                                </div>
                                <p class="text-xs text-slate-400 tracking-wide justify-self-center left-60 uppercase mt-1 hidden" id="info-bill">*Kosongkan jika All BON</p>
                            </div>
                            <div class=" w-[80%] rounded-lg">
                                <label for="kalkulasi" class="text-xs" id="label-ket">Keterangan</label>
                                <h5 class="bg-gray-500/10 backdrop-blur-2xl rounded-md px-2 py-1">Rp. <span id="kalkulasi">0</span></h5>
                                <p class="text-xs font-bold tracking-wide justify-self-center left-60 uppercase mt-1 hidden" id="info-ket-bill"></p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger w-100 fw-bold py-3 shadow" id="btn-proses">
                            <i class="fas fa-check-circle me-1"></i> PROSES TRANSAKSI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let keranjang = [];
    let member = @json($members);
    let barang = @json($barangs);
    const kreditMember =  @json($limitBon) ;
    let limitBon = 0;

    // Toggle muncul Form NIK Member
    document.getElementById('kategori_yes').addEventListener('change', function() {
        document.querySelector('.nik-form').classList.remove('hidden');
        document.getElementById('kategori').value = 'member';
    });

    document.getElementById('kategori_no').addEventListener('change', function() {
        document.querySelector('.nik-form').classList.add('hidden');
        document.getElementById('member_id').value = '';
        document.getElementById('kategori').value = 'reguler';
    });

    // Toggle Split Bill Form
    document.getElementById('metode_bayar').addEventListener('change', function() {
        // const splitForm = document.getElementById('split-bill-form');
        if(this.value === 'tunai') {
            document.getElementById('nominal-label').textContent = 'Nominal (Tunai)';
            document.getElementById('info-bill').classList.add('hidden');
            document.getElementById('info-ket-bill').classList.add('hidden');
        } else {
            document.getElementById('nominal-label').textContent = 'Split Bill (Pembayaran Tunai)';
            document.getElementById('info-bill').classList.remove('hidden');
            document.getElementById('info-ket-bill').classList.remove('hidden');
            document.getElementById('info-ket-bill').innerText = '*Maksimal Rp ' + limitBon.toLocaleString() + ' untuk BON Anggota';
            document.getElementById('nominal').classList.remove('text-greyed-600');
        }
    });


    // Cek NIK Member
    document.getElementById('nik-input').addEventListener('blur', function() {

        const nik = this.value.trim();
        if(nik === '') {
            document.getElementById('member-info').classList.add('hidden');
            return;
        }

        const memberInfo = member.find(m => m.nik === nik);
        checkLimit(memberInfo.id);
        if(memberInfo) {
            document.getElementById('member-info').textContent = `Member: ${memberInfo.nama_lengkap} (diskon 10% berlaku)`;
            document.getElementById('member-info').classList.remove('hidden');
            document.getElementById('member_id').value = memberInfo.id;
            document.getElementById('kategori').value = 'member';
            renderTable();
        } else {
            document.getElementById('member-info').textContent = 'Member tidak ditemukan.';
            document.getElementById('member-info').classList.remove('hidden');
            renderTable();
        }
        KalkulasiNominal();
    });

    // Fungsi Pencarian Barang
    document.getElementById('search-barang').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const filtered = barang.filter(b => 
            b.nama_barang.toLowerCase().includes(query) || 
            b.kode_barang.toLowerCase().includes(query)
        );
        const dropdown = document.getElementById('dropdown-barang');
        dropdown.innerHTML = '';
        if(filtered.length > 0 && query !== '') {
            filtered.forEach(b => {
                const option = document.createElement('a');
                option.classList.add('dropdown-item', 'cursor-pointer');
                option.textContent = `${b.kode_barang} - ${b.nama_barang} (Stok: ${b.stok})`;
                option.setAttribute('data-id', b.id);
                option.setAttribute('data-nama', b.nama_barang);
                option.setAttribute('data-harga', b.harga_jual);
                option.setAttribute('data-stok', b.stok);
                option.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const harga = parseFloat(this.getAttribute('data-harga'));
                    const stok = parseInt(this.getAttribute('data-stok'));

                    const existing = keranjang.find(item => item.id === id);
                    if(existing) {
                        if(existing.qty < stok) existing.qty++;
                        else alert('Stok tidak mencukupi!');
                    } else {
                        keranjang.push({ id, nama, harga, qty: 1, stok });
                    }
                    
                    renderTable();
                    KalkulasiNominal();
                    document.getElementById('search-barang').value = '';
                    dropdown.innerHTML = '';
                });
                dropdown.appendChild(option);
            });
            dropdown.classList.add('show');
        } else {
            dropdown.classList.remove('show');
        }
    });
    
    document.addEventListener('click' || 'blur', function(event) {
        const dropdown = document.getElementById('dropdown-barang');
        if (!dropdown.contains(event.target) && event.target.id !== 'search-barang') {
            dropdown.classList.remove('show');
        }
    });

    document.getElementById('nominal').addEventListener('input', function() {
        KalkulasiNominal();
    });


    
    // Fungsi Tambah Barang ke Keranjang
    const selectBarangEl = document.getElementById('select-barang');
    if (selectBarangEl) {
        selectBarangEl.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if(!option.value) return;

            const id = option.value;
            const nama = option.getAttribute('data-nama');
            const harga = parseFloat(option.getAttribute('data-harga'));
            const stok = parseInt(option.getAttribute('data-stok'));

            const existing = keranjang.find(item => item.id === id);
            if(existing) {
                if(existing.qty < stok) existing.qty++;
                else alert('Stok tidak mencukupi!');
            } else {
                keranjang.push({ id, nama, harga, qty: 1, stok });
            }
            
            renderTable();
            KalkulasiNominal();
            this.value = "";
        });
    }

    // fungsi checkLimit
    function checkLimit(memberId){
        return limitBon = kreditMember.find(m => m.member_id === memberId)?.limit || 0;
    }

    function renderTable() {
        const tbody = document.querySelector('#table-keranjang tbody');
        tbody.innerHTML = '';
        let total = 0;

        keranjang.forEach((item, index) => {
            const subtotal = item.harga * item.qty;
            total += subtotal;
            tbody.innerHTML += `
                <tr>
                    <td>${item.nama}</td>
                    <td>Rp ${item.harga.toLocaleString()}</td>
                    <td><input type="number" class="form-control form-control-sm" value="${item.qty}" onchange="updateQty(${index}, this.value)"></td>
                    <td>Rp ${subtotal.toLocaleString()}</td>
                    <td><button class="btn btn-sm btn-outline-danger" onclick="hapusItem(${index})"><i class="fas fa-times"></i></button></td>
                </tr>
            `;
        });
        const discount = memberDiscount(total);
        document.getElementById('discount').innerText = '- Rp ' + discount.toLocaleString();
        document.getElementById('total-kotor').innerText = 'Rp ' + total.toLocaleString();
        total -= discount;
        document.getElementById('display-total').innerText = 'Rp ' + total.toLocaleString();
        document.getElementById('total_harga').value = total;
    }

    // diskon member 10%
    function memberDiscount(total_harga) {
        const nik = document.getElementById('nik-input').value.trim();
        const memberInfo = member.find(m => m.nik === nik);
        if(memberInfo) {
            return total_harga * 0.1; // Diskon 10%
        }
        return 0;
        console.log(total_harga);
    }

    
    
    // fungsi kalkulasi nominal
    function KalkulasiNominal() {
        const nominal = parseFloat(document.getElementById('nominal').value) || 0;
        const total_harga = parseFloat(document.getElementById('total_harga').value) || 0;
        if(nominal < total_harga) {
            document.getElementById('kalkulasi').innerText = (total_harga - nominal).toLocaleString() + ' (Kurang)';
            document.getElementById('kalkulasi').classList.add('text-red-600');
            document.getElementById('kalkulasi').classList.remove('text-green-600');
            return;
        }else {
            document.getElementById('kalkulasi').innerText = (nominal - total_harga).toLocaleString() + ' (Kembalian)';
            document.getElementById('kalkulasi').classList.add('text-green-600');
            document.getElementById('kalkulasi').classList.remove('text-red-600');
            return;
        }
        document.getElementById('kalkulasi').innerText = (nominal - total_harga).toLocaleString();
    }

    // Fungsi Update Qty
    function updateQty(index, val) {
        if(val > keranjang[index].stok) {
            alert('Stok hanya tersedia ' + keranjang[index].stok);
            keranjang[index].qty = keranjang[index].stok;
        } else if(val < 1) {
            keranjang[index].qty = 1;
        } else {
            keranjang[index].qty = parseInt(val);
        }
        renderTable();
    }

    // Fungsi Hapus Item
    function hapusItem(index) {
        keranjang.splice(index, 1);
        renderTable();
    }

    // Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(angka);
    }

    // Proses Simpan ke Database
    document.getElementById('btn-proses').addEventListener('click', function() {
        if(keranjang.length === 0) return Swal.fire('Error', 'Keranjang masih kosong!', 'error');
        
        const data = {
            _token: document.querySelector('input[name="_token"]').value,
            user_id: document.querySelector('input[name="user_id"]').value,
            member_id: document.getElementById('member_id').value,
            kategori: document.getElementById('kategori').value,
            metode_bayar: document.getElementById('metode_bayar').value,
            total_harga: document.getElementById('total_harga').value,
            status: 'closed',
            total_tunai: 0,
            total_bon: 0,
            cart: keranjang
        };

        data.total_tunai = parseFloat(document.getElementById('nominal').value) || 0;
        data.total_harga = parseFloat(document.getElementById('total_harga').value);
        
        if(data.metode_bayar === 'tunai') {
            data.total_tunai = parseFloat(document.getElementById('nominal').value);
        } else if(data.metode_bayar === 'bon') {
            // data.total_tunai = parseFloat(document.getElementById('nominal').value);    
            data.status = 'open';
            data.total_bon = data.total_harga - data.total_tunai;
        }

        console.log(data.total_tunai, data.total_harga, data.metode_bayar);

        if(data.total_tunai < data.total_harga && data.metode_bayar === 'tunai') {
            return Swal.fire('Error', 'Nominal tunai kurang / tidak valid!', 'error');
        }

        if(data.metode_bayar === 'bon' && !data.member_id) {
            return Swal.fire('Error', 'Metode BON hanya untuk Anggota terdaftar!', 'error');
        }



        if(data.metode_bayar === 'bon' && data.total_bon > limitBon) {
            return Swal.fire({
                    icon: 'error',
                    title: 'Nominal BON Melebihi Batas',
                    text: 'Nominal BON melebihi !' + limitBon.toLocaleString() + '. Silakan sesuaikan nominal tunai atau pilih metode tunai.',
                }).then(() => {
                    document.getElementById('nominal').value = data.total_harga - limitBon;
                    document.getElementById('kalkulasi').innerText = formatRupiah(limitBon) + ' (Kurang)';
                });
        }

        Swal.fire({
            title: 'Konfirmasi Transaksi',
            text: "Pastikan data belanja sudah benar!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(data);
                fetch("{{ route('kasir.store') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                });
            }
        });
    });
</script>
@endsection