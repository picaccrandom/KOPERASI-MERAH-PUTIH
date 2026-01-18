<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Anggota | Koperasi Merah Putih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            padding: 30px 15px;
        }

        /* Panel Kaca Transparan */
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        /* Judul Tabel */
        .table-title {
            color: #ffffff;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-left: 5px solid #e63946;
            padding-left: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        /* Navigasi Tombol */
        .btn-nav {
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 18px;
            text-transform: uppercase;
            font-size: 11px;
            transition: 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: white !important;
        }

        .btn-back { background: #1d3557; } 
        .btn-add { background: #e63946; } 
        .btn-nav:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.3); }

        /* Style Tabel Utama */
        .table-custom {
            margin-top: 20px;
            background: transparent;
        }

        .table-custom thead th {
            background: rgba(230, 57, 70, 0.9) !important; /* Merah Koperasi */
            color: #ffffff !important;
            border: none;
            padding: 15px;
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* PERBAIKAN: Semua teks isian wajib HITAM PEKAT agar kelihatan */
        .table-custom tbody td {
            color: #000000 !important; 
            font-weight: 700; 
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .table-custom tbody tr:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Highlight NIK */
        .nik-highlight {
            color: #b8860b !important; /* Kuning tua/emas agar kontras */
            font-family: 'Consolas', monospace;
        }

        /* Alert Berhasil */
        .alert-custom {
            background: rgba(25, 135, 84, 0.85);
            color: white;
            border: none;
            font-weight: bold;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="glass-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="table-title m-0">DATABASE OPERASIONAL ANGGOTA</h4>
                <small class="text-white fw-bold ms-4" style="font-size: 10px; opacity: 0.8;">KOPERASI MERAH PUTIH - CORE SYSTEM</small>
            </div>
            
            <div class="d-flex gap-2">
                <a href="/dashboard" class="btn-nav btn-back">
                    <i class="fas fa-arrow-left"></i> KEMBALI
                </a>
                <a href="/tambah" class="btn-nav btn-add">
                    <i class="fas fa-user-plus"></i> TAMBAH ANGGOTA
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-custom alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="18%">NIK</th>
                        <th>NAMA LENGKAP</th>
                        <th width="15%">WHATSAPP</th>
                        <th>ALAMAT</th>
                        <th width="120" class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $m)
                    <tr>
                        <td class="nik-highlight">{{ $m->nik }}</td>
                        <td>{{ strtoupper($m->nama_lengkap) }}</td>
                        <td>{{ $m->nomor_hp ?? '-' }}</td>
                        <td>{{ $m->alamat }}</td>
                        <td class="text-center">
                            <a href="/edit/{{ $m->id }}" class="btn btn-warning btn-sm shadow-sm"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm shadow-sm" onclick="confirmDelete({{ $m->id }})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'OTORISASI HAPUS',
            text: "Data anggota akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            background: '#ffffff',
            color: '#000',
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#1d3557',
            confirmButtonText: 'YA, HAPUS DATA',
            cancelButtonText: 'BATAL'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/hapus/' + id;
            }
        })
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>