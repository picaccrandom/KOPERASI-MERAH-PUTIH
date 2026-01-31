@extends('layouts.master')

@section('content')
<style>
    /* =========================================
       1. DROPDOWN STYLING (BARANG & ANGGOTA)
       ========================================= */
    #dropdown-member, #dropdown-barang {
        background-color: white !important;
        border: 2px solid #dc3545 !important;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        z-index: 2000 !important;
        padding: 0;
        overflow: hidden;
    }

    .dropdown-item {
        background-color: white !important;
        color: #1a1a1a !important;
        border-bottom: 1px solid #eee;
        padding: 15px !important;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #fff5f5 !important;
        color: #dc3545 !important;
    }

    #dropdown-barang {
        border-color: #198754 !important;
    }

    #dropdown-barang .dropdown-item:hover {
        background-color: #f0fff4 !important;
        color: #198754 !important;
    }

    /* =========================================
       2. PANEL PEMBAYARAN STYLING (PERCANTIK)
       ========================================= */
    .payment-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }

    .status-selection {
        background: #f1f3f5;
        border-radius: 12px;
        padding: 8px;
    }

    .status-item {
        transition: all 0.3s ease;
        border-radius: 10px;
        padding: 15px 5px;
        color: #adb5bd;
    }

    /* Toggle Biru untuk Umum */
    input[id="kategori_no"]:checked + .status-item {
        background: white !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        color: #0d6efd !important;
    }

    /* Toggle Merah untuk Anggota */
    input[id="kategori_yes"]:checked + .status-item {
        background: white !important;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.1);
        color: #dc3545 !important;
    }

    /* TOTAL PANEL (DIBUAT CERAH & KONTRAS) */
    .total-display-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border: 2px solid #dc3545;
        border-radius: 15px;
        padding: 25px 15px;
        margin-bottom: 20px;
    }

    .total-label {
        font-size: 11px;
        font-weight: 800;
        color: #6c757d;
        letter-spacing: 1.5px;
        margin-bottom: 5px;
    }

    #display-total {
        font-size: 3rem !important;
        font-weight: 900 !important;
        color: #dc3545 !important;
        line-height: 1;
        text-shadow: 1px 1px 0px rgba(255,255,255,1);
    }

    /* KALKULASI & INPUT */
    .kalkulasi-box {
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 900;
        background: #ffffff;
        border: 2px solid #ced4da;
    }

    .input-bayar-custom {
        border: 2px solid #dee2e6 !important;
        border-radius: 10px !important;
        padding: 12px !important;
        font-weight: 900 !important;
        font-size: 1.2rem !important;
    }

    .font-black { font-weight: 900 !important; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
                    <span><i class="fas fa-shopping-cart me-2"></i> KERANJANG BELANJA GERAI</span>
                    <span class="badge bg-danger text-uppercase">Bumdes Nangsri</span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-12 position-relative">
                            <label class="form-label fw-bold small text-muted text-uppercase text-danger">Cari Barang (Barcode / Nama)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-dark">
                                    <i class="fas fa-search text-success"></i>
                                </span>
                                <input type="text" id="search-barang" class="form-control form-control-lg font-bold border-dark" placeholder="Masukkan Kode atau Nama Barang..." autocomplete="off">
                            </div>
                            
                            <div class="dropdown-menu w-100" id="dropdown-barang" style="max-height: 350px; overflow-y: auto;">
                                </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive lg:h-[29rem] overflow-y-auto border rounded shadow-sm">
                        <table class="table table-hover table-bordered align-middle" id="table-keranjang">
                            <thead class="bg-light sticky-top">
                                <tr class="text-center uppercase small fw-black">
                                    <th class="py-3 text-dark">Nama Barang</th>
                                    <th width="150" class="text-dark">Harga</th>
                                    <th width="120" class="text-dark">Qty</th>
                                    <th width="180" class="text-dark">Subtotal</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card payment-card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold uppercase text-center py-3">
                    <i class="fas fa-money-bill-wave me-2"></i> PANEL PEMBAYARAN
                </div>
                <div class="card-body p-4">
                    <form id="form-transaksi">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="member_id" id="member_id" value="">
                        <input type="hidden" name="total_harga" id="total_harga" value="0">
                        <input type="hidden" name="kategori" id="kategori" value="reguler">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small uppercase text-muted mb-2 d-block text-center">Status Pelanggan</label>
                            <div class="d-flex justify-content-around status-selection shadow-sm">
                                <label class="text-center cursor-pointer mb-0 flex-grow-1 mx-1">
                                    <input type="radio" name="kat_radio" id="kategori_no" value="reguler" class="sr-only peer" checked>
                                    <div class="status-item">
                                        <i class="fas fa-users fa-2x mb-1"></i>
                                        <p class="mb-0 small fw-bold uppercase">Umum</p>
                                    </div>
                                </label>
                                <label class="text-center cursor-pointer mb-0 flex-grow-1 mx-1">
                                    <input type="radio" name="kat_radio" id="kategori_yes" value="member" class="sr-only peer">
                                    <div class="status-item">
                                        <i class="fas fa-id-card fa-2x mb-1"></i>
                                        <p class="mb-0 small fw-bold uppercase">Anggota</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="nik-form mb-4 hidden animate-in slide-in-from-top-2">
                            <div class="p-3 border-start border-4 border-danger bg-danger/5 position-relative rounded-end shadow-sm">
                                <label class="form-label fw-black text-danger small uppercase tracking-tighter">Cari NIK / Nama Anggota</label>
                                <input type="text" id="nik-input" class="form-control border-dark font-bold shadow-sm" placeholder="Ketik NIK atau Nama..." autocomplete="off">
                                
                                <div class="dropdown-menu w-100 shadow-lg" id="dropdown-member" style="max-height: 250px; overflow-y: auto; z-index: 1060;">
                                    </div>

                                <div id="member-info" class="mt-2 p-2 bg-success text-white rounded text-xs font-bold hidden shadow-sm border border-white"></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted uppercase">Metode Pembayaran</label>
                            <select name="metode_bayar" id="metode_bayar" class="form-select border-dark fw-black text-primary py-2 shadow-sm" style="border-radius: 10px;">
                                <option value="tunai">CASH / TUNAI</option>
                                <option value="bon">BON / PIUTANG ANGGOTA</option>
                            </select>
                        </div>

                        <div class="total-display-box shadow-sm text-center">
                            <div class="d-flex justify-content-between px-3 mb-1">
                                <span class="text-muted small fw-bold uppercase">Bruto</span>
                                <span id="total-kotor" class="fw-bold text-dark">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between px-3 mb-3 border-bottom pb-2">
                                <span class="text-success small fw-bold uppercase">Potongan (10%)</span>
                                <span id="discount" class="fw-bold text-success">- Rp 0</span>
                            </div>
                            <p class="total-label uppercase mb-1">Total Tagihan Akhir</p>
                            <h1 class="fw-black" id="display-total">Rp 0</h1>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label id="nominal-label" class="text-[10px] font-black uppercase text-muted">Dibayar (Tunai)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-dark text-xs font-bold" style="border-radius: 10px 0 0 10px;">Rp</span>
                                    <input type="number" name="nominal" id="nominal" class="form-control border-dark input-bayar-custom text-danger shadow-sm" placeholder="0" style="border-radius: 0 10px 10px 0 !important;">
                                </div>
                                <p id="info-bill" class="text-[9px] text-danger mt-1 hidden font-bold text-uppercase">*Kosongkan jika Full BON</p>
                            </div>
                            <div class="col-6">
                                <label id="label-ket" class="text-[10px] font-black uppercase text-muted">Kembalian</label>
                                <div class="kalkulasi-box shadow-sm text-truncate" id="kalkulasi">Rp 0</div>
                                <p id="info-ket-bill" class="text-[9px] text-danger mt-1 font-black hidden uppercase"></p>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger w-100 fw-black py-3 shadow-lg transform active:scale-95 transition-all mb-4" id="btn-proses" style="border-radius: 12px; font-size: 1.1rem; letter-spacing: 1px;">
                            <i class="fas fa-check-circle me-2"></i> SELESAIKAN TRANSAKSI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    /**
     * =========================================
     * 1. INISIALISASI DATA DARI CONTROLLER
     * =========================================
     */
    let keranjang = [];
    const memberData = @json($members);
    const barangData = @json($barangs);
    const kreditMember = @json($limitBon);
    let limitBonAnggota = 0;

    const inputNik = document.getElementById('nik-input');
    const dropdownMember = document.getElementById('dropdown-member');
    const inputSearchBarang = document.getElementById('search-barang');
    const dropdownBarang = document.getElementById('dropdown-barang');

    /**
     * =========================================
     * 2. LOGIKA AUTOCOMPLETE ANGGOTA
     * =========================================
     */
    inputNik.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        dropdownMember.innerHTML = '';

        if (query.length < 1) {
            dropdownMember.classList.remove('show');
            return;
        }

        const filtered = memberData.filter(m => 
            (m.nik && m.nik.toLowerCase().includes(query)) || 
            (m.nama_lengkap && m.nama_lengkap.toLowerCase().includes(query))
        );

        if (filtered.length > 0) {
            dropdownMember.classList.add('show');
            filtered.forEach(m => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="line-height: 1.2;">
                            <small class="text-danger fw-bold d-block">${m.nik}</small>
                            <strong class="text-dark text-uppercase font-black" style="font-size: 1rem;">${m.nama_lengkap}</strong>
                        </div>
                        <i class="fas fa-user-plus text-muted fa-lg"></i>
                    </div>`;
                item.onclick = function() {
                    inputNik.value = m.nik;
                    pilihAnggota(m);
                    dropdownMember.classList.remove('show');
                };
                dropdownMember.appendChild(item);
            });
        } else {
            dropdownMember.classList.add('show');
            dropdownMember.innerHTML = '<div class="p-3 text-center text-muted">Anggota tidak ditemukan...</div>';
        }
    });

    function pilihAnggota(m) {
        document.getElementById('member_id').value = m.id;
        const infoBox = document.getElementById('member-info');
        infoBox.innerHTML = `<i class="fas fa-check-circle me-1"></i> ${m.nama_lengkap}`;
        infoBox.classList.remove('hidden');
        
        // Cari Limit BON
        const limitObj = kreditMember.find(l => l.member_id === m.id);
        limitBonAnggota = limitObj ? limitObj.limit : 0;
        
        if(document.getElementById('metode_bayar').value === 'bon') {
            document.getElementById('info-ket-bill').innerText = '*MAKS. PIUTANG: RP ' + limitBonAnggota.toLocaleString();
        }
        renderTable();
    }

    /**
     * =========================================
     * 3. LOGIKA AUTOCOMPLETE BARANG
     * =========================================
     */
    inputSearchBarang.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        dropdownBarang.innerHTML = '';

        if(query.length < 1) {
            dropdownBarang.classList.remove('show');
            return;
        }

        const filtered = barangData.filter(b => 
            (b.nama_barang && b.nama_barang.toLowerCase().includes(query)) || 
            (b.kode_barang && b.kode_barang.toLowerCase().includes(query))
        );

        if(filtered.length > 0) {
            dropdownBarang.classList.add('show');
            filtered.forEach(b => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="flex: 1;">
                            <span class="badge bg-danger mb-1">${b.kode_barang}</span>
                            <strong class="text-dark d-block text-uppercase font-black" style="font-size: 1rem;">${b.nama_barang}</strong>
                        </div>
                        <div class="text-end" style="min-width: 130px;">
                            <span class="d-block text-primary fw-black" style="font-size: 1.1rem;">Rp ${b.harga_jual.toLocaleString()}</span>
                            <small class="badge ${b.stok < 10 ? 'bg-warning text-dark' : 'bg-light text-muted'} border">Stok: ${b.stok}</small>
                        </div>
                    </div>`;
                item.onclick = function() {
                    tambahKeKeranjang(b);
                    inputSearchBarang.value = '';
                    dropdownBarang.classList.remove('show');
                };
                dropdownBarang.appendChild(item);
            });
        } else {
            dropdownBarang.classList.add('show');
            dropdownBarang.innerHTML = '<div class="p-3 text-center text-muted">Barang tidak ditemukan...</div>';
        }
    });

    function tambahKeKeranjang(b) {
        const exist = keranjang.find(item => item.id === b.id);
        if(exist) {
            if(exist.qty < b.stok) {
                exist.qty++;
            } else {
                Swal.fire('Stok Habis!', `Hanya tersedia ${b.stok} unit.`, 'warning');
            }
        } else {
            keranjang.push({ id: b.id, nama: b.nama_barang, harga: b.harga_jual, qty: 1, stok: b.stok });
        }
        renderTable();
    }

    /**
     * =========================================
     * 4. LOGIKA RENDER TABLE & KALKULASI HARGA
     * =========================================
     */
    function renderTable() {
        const tbody = document.querySelector('#table-keranjang tbody');
        tbody.innerHTML = '';
        let bruto = 0;

        keranjang.forEach((item, index) => {
            const rowTotal = item.harga * item.qty;
            bruto += rowTotal;
            tbody.innerHTML += `
                <tr>
                    <td class="text-uppercase small font-black">${item.nama}</td>
                    <td class="text-end">Rp ${item.harga.toLocaleString()}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm text-center font-black" value="${item.qty}" min="1" onchange="updateQty(${index}, this.value)">
                    </td>
                    <td class="text-end text-danger fw-black">Rp ${rowTotal.toLocaleString()}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-link text-danger" onclick="hapusItem(${index})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>`;
        });

        // Hitung Diskon Member 10% (Hanya untuk Tunai)
        let diskon = 0;
        const isMember = document.getElementById('member_id').value !== '';
        const isTunai = document.getElementById('metode_bayar').value === 'tunai';
        
        if(isMember && isTunai) {
            diskon = bruto * 0.1;
        }

        const netto = bruto - diskon;
        document.getElementById('total-kotor').innerText = 'Rp ' + bruto.toLocaleString();
        document.getElementById('discount').innerText = '- Rp ' + diskon.toLocaleString();
        document.getElementById('display-total').innerText = 'Rp ' + netto.toLocaleString();
        document.getElementById('total_harga').value = netto;
        
        KalkulasiNominal();
    }

    function updateQty(idx, val) {
        const v = parseInt(val) || 1;
        if(v > keranjang[idx].stok) {
            Swal.fire('Limit!', `Maksimal: ${keranjang[idx].stok}`, 'warning');
            keranjang[idx].qty = keranjang[idx].stok;
        } else {
            keranjang[idx].qty = v < 1 ? 1 : v;
        }
        renderTable();
    }

    function hapusItem(idx) {
        keranjang.splice(idx, 1);
        renderTable();
    }

    /**
     * =========================================
     * 5. LOGIKA KALKULASI PEMBAYARAN (KEMBALIAN)
     * =========================================
     */
    document.getElementById('nominal').addEventListener('input', KalkulasiNominal);

    function KalkulasiNominal() {
        const bayarInput = parseFloat(document.getElementById('nominal').value) || 0;
        const totalTagihan = parseFloat(document.getElementById('total_harga').value) || 0;
        const selisih = bayarInput - totalTagihan;
        const dispKalkulasi = document.getElementById('kalkulasi');
        const labelKet = document.getElementById('label-ket');
        
        if(selisih >= 0) {
            dispKalkulasi.innerText = 'Rp ' + selisih.toLocaleString();
            dispKalkulasi.className = 'kalkulasi-box font-black text-center border-success text-success bg-white shadow-sm';
            labelKet.innerText = 'Kembalian';
        } else {
            dispKalkulasi.innerText = 'Rp ' + Math.abs(selisih).toLocaleString();
            dispKalkulasi.className = 'kalkulasi-box font-black text-center border-danger text-danger bg-white shadow-sm';
            labelKet.innerText = (document.getElementById('metode_bayar').value === 'bon') ? 'Sisa Piutang' : 'Kurang';
        }
    }

    /**
     * =========================================
     * 6. LOGIKA UI TOGGLE & EVENT LAINNYA
     * =========================================
     */
    document.getElementById('kategori_yes').addEventListener('change', () => {
        document.querySelector('.nik-form').classList.remove('hidden');
        document.getElementById('kategori').value = 'member';
    });

    document.getElementById('kategori_no').addEventListener('change', () => {
        document.querySelector('.nik-form').classList.add('hidden');
        document.getElementById('member_id').value = '';
        document.getElementById('kategori').value = 'reguler';
        inputNik.value = '';
        document.getElementById('member-info').classList.add('hidden');
        limitBonAnggota = 0;
        renderTable();
    });

    document.getElementById('metode_bayar').addEventListener('change', function() {
        const isBon = this.value === 'bon';
        document.getElementById('nominal-label').textContent = isBon ? 'Split: Bayar Tunai' : 'Dibayar (Tunai)';
        document.getElementById('info-bill').classList.toggle('hidden', !isBon);
        document.getElementById('info-ket-bill').classList.toggle('hidden', !isBon);
        
        if(isBon) {
            document.getElementById('info-ket-bill').innerText = '*MAKS. PIUTANG: RP ' + limitBonAnggota.toLocaleString();
        }
        renderTable();
    });

    /**
     * =========================================
     * 7. PROSES TRANSAKSI (SIMPAN & CETAK)
     * =========================================
     */
    document.getElementById('btn-proses').addEventListener('click', function() {
        if(keranjang.length === 0) {
            return Swal.fire('Oops!', 'Keranjang masih kosong.', 'info');
        }

        const totalHrg = parseFloat(document.getElementById('total_harga').value);
        const nominalTunai = parseFloat(document.getElementById('nominal').value) || 0;
        const metode = document.getElementById('metode_bayar').value;

        // Validasi Pembayaran
        if(metode === 'tunai' && nominalTunai < totalHrg) {
            return Swal.fire('Uang Kurang!', 'Bayar tunai belum mencukupi.', 'error');
        }
        
        if(metode === 'bon') {
            if(!document.getElementById('member_id').value) {
                return Swal.fire('Wajib Member!', 'Hanya anggota yang bisa mengambil piutang.', 'error');
            }
            if((totalHrg - nominalTunai) > limitBonAnggota) {
                return Swal.fire('Limit BON!', 'Melebihi jatah piutang anggota.', 'error');
            }
        }

        Swal.fire({
            title: 'Selesaikan Pembayaran?',
            text: "Data akan disimpan dan stok akan terpotong!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Bayar Sekarang!'
        }).then((result) => {
            if (result.isConfirmed) {
                const payload = {
                    _token: "{{ csrf_token() }}",
                    user_id: document.querySelector('input[name="user_id"]').value,
                    member_id: document.getElementById('member_id').value,
                    kategori: document.getElementById('kategori').value,
                    metode_bayar: metode,
                    total_harga: totalHrg,
                    total_tunai: nominalTunai,
                    total_bon: (metode === 'bon') ? (totalHrg - nominalTunai) : 0,
                    status: 'closed',
                    cart: keranjang
                };

                fetch("{{ route('kasir.store') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(res => {
                    if(res.success) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Transaksi Berhasil!', 
                            timer: 1500, 
                            showConfirmButton: false 
                        }).then(() => {
                            window.location.href = "/cetak-struk/" + res.data.kode_transaksi;
                        });
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error Sistem', 'Terjadi kesalahan koneksi server.', 'error');
                });
            }
        });
    });

    /**
     * TUTUP DROPDOWN SAAT KLIK DI LUAR ELEMEN
     */
    document.addEventListener('click', function(e) {
        if (!inputNik.contains(e.target) && !dropdownMember.contains(e.target)) {
            dropdownMember.classList.remove('show');
        }
        if (!inputSearchBarang.contains(e.target) && !dropdownBarang.contains(e.target)) {
            dropdownBarang.classList.remove('show');
        }
    });
</script>
@endsection