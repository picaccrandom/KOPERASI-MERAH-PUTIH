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
        <h5 class="fw-bold text-dark mb-0"> <i class="fas fa-users-cog me-2"></i> MASTER USER SYSTEM
        </h5>
        
        <button class="btn btn-sm btn-danger fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-1"></i> TAMBAH USER
        </button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger py-2 shadow-sm border-0 mb-3" style="border-radius: 10px; font-size: 12px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        <th class="text-center">NO</th>
                        <th>NAMA LENGKAP</th>
                        <th>USERNAME</th>
                        <th>LEVEL ACCESS</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $no => $user)
                    <tr>
                        <td class="text-center">{{ $no + 1 }}</td>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->username }}</td> 
                        <td><span class="badge bg-primary">SUPER ADMIN</span></td>
                        <td class="text-center"><span class="badge bg-success">ACTIVE</span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white me-1 btn-edit" 
                                    data-id="{{ $user->id }}" 
                                    data-name="{{ $user->name }}" 
                                    data-username="{{ $user->username }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus me-2"></i>TAMBAH USER BARU</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA LENGKAP</label>
                        <input type="text" name="name" class="form-control form-control-sm border-dark shadow-none" required value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">USERNAME</label>
                        <input type="text" name="username" class="form-control form-control-sm border-dark shadow-none" required value="{{ old('username') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">PASSWORD</label>
                        <input type="password" name="password" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-bold">SIMPAN USER</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>EDIT DATA USER</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">NAMA LENGKAP</label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">USERNAME</label>
                        <input type="text" name="username" id="edit_username" class="form-control form-control-sm border-dark shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">PASSWORD (Kosongkan jika tidak diganti)</label>
                        <input type="password" name="password" class="form-control form-control-sm border-dark shadow-none">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-sm btn-info text-white fw-bold">UPDATE USER</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // FUNGSI KONFIRMASI HAPUS (Mirip Menu Anggota)
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.form-delete');
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus user ini? Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e63946',
                cancelButtonColor: '#1d3557',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // FUNGSI AKTIFKAN TOMBOL EDIT
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const username = this.getAttribute('data-username');
            
            // Set data ke dalam input modal
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_username').value = username;
            
            // Set action form secara dinamis
            document.getElementById('formEdit').action = '/admin/master-user/' + id;
            
            // Tampilkan modal edit
            var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
            modalEdit.show();
        });
    });
</script>
@endsection