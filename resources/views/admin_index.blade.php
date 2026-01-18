<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin System Control | Koperasi Merah Putih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed;
            background-size: cover; margin: 0; min-height: 100vh; font-family: 'Inter', sans-serif;
        }

        /* HEADER BARIS MENU BIRU */
        .admin-nav-bar { 
            background: #4a708b; 
            border-bottom: 2px solid #e63946; 
            padding: 0 20px; 
            height: 40px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .nav-link-admin {
            color: white;
            text-decoration: none;
            padding: 0 15px;
            font-size: 13px;
            font-weight: bold;
            height: 40px;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        /* Efek putih teks hitam saat dropdown terbuka */
        .nav-link-admin:hover, .show > .nav-link-admin {
            background: #ffffff;
            color: #000000 !important;
        }

        .dropdown-menu {
            background: #34495e; 
            border-radius: 0;
            margin-top: 0;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .dropdown-item {
            color: white;
            font-size: 13px;
            padding: 10px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .dropdown-item:hover {
            background: #e63946;
            color: white;
        }

        /* PANEL UTAMA */
        .glass-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            padding: 20px;
        }

        .admin-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .admin-title {
            color: white;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 20px;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        .status-box {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 15px 30px;
            display: inline-flex;
            gap: 40px;
            margin-top: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-kembali {
            background: #1d3557;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 8px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 40px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .btn-kembali:hover {
            background: #e63946;
            color: white;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <nav class="admin-nav-bar">
        <div class="d-flex h-100">
            <div class="dropdown h-100">
                <a class="nav-link-admin dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    Otorisasi User
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/admin/master-user">Master User</a></li>
                    <li><a class="dropdown-item" href="/admin/ganti-password">Ganti Password</a></li>
                </ul>
            </div>
            <a class="nav-link-admin" href="/anggota">DataBase Anggota</a>
        </div>
        <div style="font-size: 11px; font-weight: bold;">
            <i class="fas fa-calendar-alt me-1"></i> {{ date('l, d F Y') }} | <span class="badge bg-danger">ONLINE</span>
        </div>
    </nav>

    <div class="glass-container">
        <div class="admin-panel">
            <img src="https://cdn-icons-png.flaticon.com/512/6024/6024190.png" width="120" style="filter: brightness(0) invert(1); opacity: 0.9;">
            <h2 class="admin-title">ADMINISTRATOR SYSTEM CONTROL</h2>
            
            <div class="status-box">
                <div class="text-start">
                    <small class="text-white-50 d-block" style="font-size: 9px; letter-spacing: 1px;">SYSTEM STATUS</small>
                    <span class="text-success fw-bold" style="font-size: 13px;">
                        <i class="fas fa-circle fa-xs me-1"></i> CORE ENGINE ACTIVE
                    </span>
                </div>
                <div class="text-start border-start ps-4" style="border-color: rgba(255,255,255,0.1) !important;">
                    <small class="text-white-50 d-block" style="font-size: 9px; letter-spacing: 1px;">IP ADDRESS</small>
                    <span class="text-white fw-bold" style="font-size: 13px;">{{ request()->ip() }}</span>
                </div>
            </div>

            <br>
            <a href="/dashboard" class="btn-kembali">
                <i class="fas fa-arrow-left"></i> KEMBALI KE MENU UTAMA
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>