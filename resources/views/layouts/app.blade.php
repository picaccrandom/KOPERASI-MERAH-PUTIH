<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Terpadu | Koperasi Merah Putih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed; background-size: cover; margin: 0; min-height: 100vh; font-family: 'Inter', sans-serif; }
        
        /* HEADER 1 (TETAP PUTIH) */
        .top-header { background: #ffffff; height: 95px; display: flex; align-items: center; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .main-title { font-size: 22px; font-weight: 850; text-shadow: 1px 1px 0px #000 !important; width: 100%; text-align: center; margin: 0; }
        .sub-title { font-size: 15px; font-weight: 700; width: 100%; text-align: center; margin: 0; }
        .sub-title2 { margin-top: 15px !important; display: block; font-weight: 700; color: #000 !important; letter-spacing: 5px; font-size: 11px; text-align: center; width: 100%; }
        
        /* HEADER 2 / STATUS BAR BIRU */
        .status-bar { background: #4a708b; border-bottom: 2px solid #e63946; padding: 0 20px; height: 38px; display: flex; justify-content: space-between; align-items: center; color: white; }
        
        /* Navigasi Admin */
        .nav-link-admin { color: white; text-decoration: none; padding: 0 15px; font-size: 13px; font-weight: bold; height: 38px; display: flex; align-items: center; transition: 0.2s; cursor: pointer; }
        
        /* Efek Putih Teks Hitam saat Hover/Aktif */
        .nav-link-admin:hover, .show > .nav-link-admin { background: #ffffff; color: #000000 !important; }
        
        .dropdown-menu { background: #34495e; border-radius: 0; margin-top: 0; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .dropdown-item { color: white; font-size: 13px; padding: 8px 20px; }
        .dropdown-item:hover { background: #e63946; color: white; }

        .nav-icons { position: absolute; right: 25px; top: 35px; display: flex; gap: 20px; }
    </style>
</head>
<body>
    <div class="top-header">
        <div class="nav-icons">
            <a href="/dashboard" style="color: #1d3557;"><i class="fas fa-home fa-lg"></i></a>
            <a href="/logout" style="color: #e63946;"><i class="fas fa-sign-out-alt fa-lg"></i></a>
        </div>
        <div class="w-100">
            <h1 class="main-title">SISTEM INFORMASI TERPADU</h1>
            <h2 class="sub-title">KOPERASI <span style="color: #e63946;">MERAH</span> PUTIH</h2>
            <span class="sub-title2">DESA NANGSRI</span>
        </div>
    </div>

    <div class="status-bar">
        <div class="d-flex h-100 align-items-center">
            
            <div id="default-status-text">
                <span class="fw-bold" style="font-size: 12px;">YOGATECHSOLUTION | CORE SYSTEM V1.0</span>
            </div>

            <div id="admin-nav-menu" class="d-none h-100">
                <div class="dropdown h-100 d-inline-block">
                    <a class="nav-link-admin dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Otorisasi User
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/master-user">Master User</a></li>
                        <li><a class="dropdown-item" href="/admin/ganti-password">Ganti Password</a></li>
                    </ul>
                </div>
                <a class="nav-link-admin d-inline-flex" href="/anggota">DataBase Anggota</a>
            </div>

        </div>

        <div style="font-size: 11px; font-weight: bold;">
            <i class="fas fa-calendar-alt"></i> {{ date('l, d F Y') }} | <span class="badge bg-danger">ONLINE</span>
        </div>
    </div>

    <div class="container-fluid py-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Fungsi ini dipanggil dari dashboard.blade.php
        function showAdminMenu() {
            const textDefault = document.getElementById('default-status-text');
            const menuAdmin = document.getElementById('admin-nav-menu');

            if(textDefault && menuAdmin) {
                textDefault.classList.add('d-none'); // Sembunyikan tulisan Yogatech
                menuAdmin.classList.remove('d-none'); // Munculkan menu Otorisasi
                menuAdmin.classList.add('d-flex'); // Pastikan layoutnya sejajar
            }
        }
    </script>
</body>
</html>