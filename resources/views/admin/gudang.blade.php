@extends('layouts.master')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 25px;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-warehouse me-2"></i> MASTER GUDANG (BARANG)</h5>
        <div class="">
            <button class="btn btn-sm btn-danger fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                <i class="fas fa-plus me-1"></i> TAMBAH BARANG
            </button>
            |
            <button class="btn btn-sm btn-success fw-bold shadow-sm px-3 me-2" data-bs-toggle="modal" data-bs-target="#modalStokMasuk">
                <i class="fas fa-download me-1"></i> STOK MASUK
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success py-2 shadow-sm border-0 mb-3" style="border-radius: 10px; font-size: 12px;">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="glass-card shadow">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background: #1d3557; color: white;">
                    <tr>
                        <th class="text-center">KODE</th>
                        <th>NAMA BARANG</th>
                        <th>KATEGORI</th>
                        <th class="text-center">STOK</th>
                        <th>SATUAN</th>
                        <th>HARGA JUAL</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangs as $barang)
                    <tr>
                        <td class="text-center fw-bold text-primary">{{ $barang->kode_barang }}</td>
                        <td class="fw-bold">{{ $barang->nama_barang }}</td>
                        <td><span class="badge bg-secondary">{{ $barang->kategori }}</span></td>
                        <td class="text-center">
                            <span class="badge {{ $barang->stok <= 5 ? 'bg-danger' : 'bg-success' }}">
                                {{ $barang->stok }}
                            </span>
                        </td>
                        <td>{{ $barang->satuan }}</td>
                        <td>Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white btn-edit" 
                                    data-json="{{ json_encode($barang) }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('gudang.destroy', $barang->id) }}" method="POST" class="d-inline form-delete">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus me-2"></i>TAMBAH DATA BARANG</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('gudang.store') }}" method="POST">
                @csrf
                <div class="modal-body row text-dark">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">KODE BARANG</label>
                        <input type="text" name="kode_barang" class="form-control form-control-sm border-dark shadow-none" placeholder="Contoh: BRG-001" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">NAMA BARANG</label>
                        <input type="text" name="nama_barang" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">KATEGORI</label>
                        <select name="kategori" class="form-select form-select-sm border-dark shadow-none">
                            <option value="Sembako">Sembako</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="ATK">ATK</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold small">STOK AWAL</label>
                        <input type="number" name="stok" class="form-control form-control-sm border-dark shadow-none" value="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold small">SATUAN</label>
                        <input type="text" name="satuan" class="form-control form-control-sm border-dark shadow-none" placeholder="Pcs/Box/Kg" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">HARGA BELI (Rp)</label>
                        <input type="number" name="harga_beli" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">HARGA JUAL (Rp)</label>
                        <input type="number" name="harga_jual" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold">SIMPAN BARANG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>EDIT DATA BARANG</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditBarang" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body row text-dark">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">KODE BARANG</label>
                        <input type="text" name="kode_barang" id="edit_kode" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">NAMA BARANG</label>
                        <input type="text" name="nama_barang" id="edit_nama" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">KATEGORI</label>
                        <select name="kategori" id="edit_kategori" class="form-select form-select-sm border-dark shadow-none">
                            <option value="Sembako">Sembako</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="ATK">ATK</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">SATUAN</label>
                        <input type="text" name="satuan" id="edit_satuan" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">HARGA BELI (Rp)</label>
                        <input type="number" name="harga_beli" id="edit_harga_beli" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">HARGA JUAL (Rp)</label>
                        <input type="number" name="harga_jual" id="edit_harga_jual" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sm btn-info text-white fw-bold">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStokMasuk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>INPUT STOK MASUK</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('stok.masuk.store') }}" method="POST">
                @csrf
                <div class="modal-body text-dark">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">PILIH BARANG</label>
                        <select name="barang_id" class="form-select border-dark shadow-none" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama_barang }} (Stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">JUMLAH MASUK</label>
                        <input type="number" name="jumlah_masuk" class="form-control border-dark shadow-none" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">TANGGAL MASUK</label>
                        <input type="date" name="tanggal_masuk" class="form-control border-dark shadow-none" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sm btn-success fw-bold">TAMBAH STOK</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SCRIPT EDIT BARANG
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const data = JSON.parse(this.getAttribute('data-json'));
            
            // Isi data ke dalam modal edit
            document.getElementById('edit_kode').value = data.kode_barang;
            document.getElementById('edit_nama').value = data.nama_barang;
            document.getElementById('edit_kategori').value = data.kategori;
            document.getElementById('edit_satuan').value = data.satuan;
            document.getElementById('edit_harga_beli').value = data.harga_beli;
            document.getElementById('edit_harga_jual').value = data.harga_jual;
            
            // Atur URL form action untuk update
            document.getElementById('formEditBarang').action = '/admin/gudang/' + data.id;
            
            // Tampilkan modal
            new bootstrap.Modal(document.getElementById('modalEditBarang')).show();
        });
    });

    // SCRIPT HAPUS BARANG
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.form-delete');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Yakin ingin menghapus barang ini dari gudang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e63946',
                cancelButtonColor: '#1d3557',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
</script>
@endsection