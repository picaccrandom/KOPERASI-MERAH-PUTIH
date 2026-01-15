<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Koperasi - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, sans-serif; color: #343a40; }
        .container-fluid { 
            margin: 20px auto; padding: 25px; background: #fff; 
            border: 1px solid #dee2e6; box-shadow: 0 4px 8px rgba(0,0,0,0.05); 
            border-radius: 8px; max-width: calc(100% - 40px);
        }
        .header-title { 
            font-size: 14px; font-weight: bold; text-transform: uppercase;
            border-bottom: 2px solid #007bff; display: inline-block; padding-bottom: 3px; margin-bottom: 20px;
        }
        .table { font-size: 12px; border: 1px solid #dee2e6; }
        .table thead { background-color: #f8f9fa; color: #555; }
        .btn-toolkit { padding: 4px 10px; font-size: 11px; font-weight: bold; border-radius: 4px; text-transform: uppercase; }
        .alert-toolkit { font-size: 12px; border-radius: 4px; border-left: 4px solid #198754; }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="header-title">Database Operasional Anggota</h5>
            <a href="/tambah" class="btn btn-primary btn-toolkit shadow-sm"><i class="fas fa-plus me-1"></i> Add Member</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-toolkit alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="15%">NIK</th>
                        <th>NAMA LENGKAP</th>
                        <th width="15%">NO. WHATSAPP</th>
                        <th>ALAMAT</th>
                        <th width="120" class="text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                    <tr>
                        <td class="fw-bold text-primary">{{ $m->nik }}</td>
                        <td>{{ strtoupper($m->nama_lengkap) }}</td>
                        <td>{{ $m->nomor_hp }}</td>
                        <td class="text-muted small">{{ $m->alamat }}</td>
                        <td class="text-center">
                            <a href="/edit/{{ $m->id }}" class="btn btn-outline-warning btn-toolkit me-1"><i class="fas fa-edit"></i></a>
                            <a href="/hapus/{{ $m->id }}" class="btn btn-outline-danger btn-toolkit" onclick="return confirmDelete(event, this.href)"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(e, url) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Data anggota akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        }
    </script>
</body>
</html>