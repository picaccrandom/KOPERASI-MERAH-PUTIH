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
                            <select id="select-barang" class="form-select border-dark">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}" data-nama="{{ $b->nama_barang }}" data-harga="{{ $b->harga_jual }}" data-stok="{{ $b->stok }}">
                                        {{ $b->kode_barang }} - {{ $b->nama_barang }} (Stok: {{ $b->stok }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
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
                        <div class="mb-3">
                            <label class="form-label fw-bold small">PILIH ANGGOTA (KOSONGKAN JIKA UMUM)</label>
                            <select name="member_id" id="member_id" class="form-select border-dark">
                                <option value="">-- Pelanggan Umum --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">METODE BAYAR</label>
                            <select name="metode_bayar" id="metode_bayar" class="form-select border-dark fw-bold text-primary">
                                <option value="Tunai">TUNAI (CASH)</option>
                                <option value="Bon">BON (KHUSUS ANGGOTA)</option>
                            </select>
                        </div>

                        <div class="bg-light p-3 rounded mb-3 text-center">
                            <h6 class="fw-bold text-muted mb-1">TOTAL BELANJA</h6>
                            <h2 class="fw-bold text-danger" id="display-total">Rp 0</h2>
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

    // Fungsi Tambah Barang ke Keranjang
    document.getElementById('select-barang').addEventListener('change', function() {
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
        this.value = "";
    });

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

        document.getElementById('display-total').innerText = 'Rp ' + total.toLocaleString();
    }

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

    function hapusItem(index) {
        keranjang.splice(index, 1);
        renderTable();
    }

    // Proses Simpan ke Database
    document.getElementById('btn-proses').addEventListener('click', function() {
        if(keranjang.length === 0) return Swal.fire('Error', 'Keranjang masih kosong!', 'error');
        
        const data = {
            _token: document.querySelector('input[name="_token"]').value,
            member_id: document.getElementById('member_id').value,
            metode_bayar: document.getElementById('metode_bayar').value,
            total_harga: keranjang.reduce((a, b) => a + (b.harga * b.qty), 0),
            cart: keranjang
        };

        if(data.metode_bayar === 'Bon' && !data.member_id) {
            return Swal.fire('Error', 'Metode BON hanya untuk Anggota terdaftar!', 'error');
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