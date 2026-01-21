<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YogaTechSolution | Core System V1.0</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --koperasi-red: #e63946;
            --koperasi-dark: #1d3557;
            --koperasi-red-head: #000000;
            --koperasi-dark-head: #ffffff;
        }

        body { 
            background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        /* --- HEADER SECTION --- */
        .top-header {
            height: 95px; 
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #ffffff; 
            padding: 0 !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .header-left {
            width: 220px;
            height: 100%;
            padding-left: 40px;
            background: #ffffff;
            display: flex;
            align-items: center;
        }

        .center-brand {
            flex-grow: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            background: linear-gradient(90deg, #ffffff 0%, #b0b0b0 50%, #ffffff 100%);
        }

        .header-right {
            width: 220px;
            height: 100%;
            padding-right: 40px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .main-title {
            font-size: 22px;
            letter-spacing: 3px;
            color: #ffffff;
            font-weight: 850;
            margin: 0;
            margin-bottom: 5px;
            text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
        }

        .sub-title {
            font-size: 15px;
            letter-spacing: 5px;
            margin-bottom: 5px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .txt-merah {
            color: #ff0000 !important;
            font-weight: 800;
            text-shadow: 1px 1px 0px #000;
        }

        .txt-putih {
            color: #ffffff !important;
            font-weight: 800;
            text-shadow: 1px 1px 0px #000;
        }

        .sub-title2 {
            font-size: 13px;
            letter-spacing: 5px;
            color: #000000 !important; 
            font-weight: 700;
            margin: 0;
            margin-top: 6px; 
            text-transform: uppercase;
        }

        .logo-koperasi {
            height: 65px;
            width: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .btn-home { color: var(--koperasi-dark); font-size: 24px; transition: 0.3s; }
        .btn-home:hover { color: var(--koperasi-red); transform: scale(1.1); }

        /* --- NAVIGATION LOG --- */
        .nav-header {
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 8px 30px;
        }

        #txtTanggal { 
            font-size: 11px; 
            font-weight: 600; 
            color: #000000 !important; 
        }

        .fa-calendar-alt { color: #000000 !important; }

        .text-muted-custom {
            color: #333333;
            font-weight: 700;
            letter-spacing: 1px; 
            font-size: 9px;
        }

        /* --- STYLE MENU ADMIN BARU --- */
        .nav-link-admin { 
            text-decoration: none; 
            color: #000; 
            font-weight: 800; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            padding: 5px 10px;
        }
        .nav-link-admin:hover, .show > .nav-link-admin { color: #e63946 !important; }
        .dropdown-menu { background: #1d3557; border-radius: 0; border: none; }
        .dropdown-item { color: white !important; font-size: 11px; font-weight: 600; padding: 8px 20px; }
        .dropdown-item:hover { background: #e63946 !important; }

        .btn-logout {
            background: #c11111;
            border: none;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 4px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="top-header">
        <div class="header-left">
            <img src="{{ asset('img/logo-koperasi.png') }}" class="logo-koperasi" alt="Logo Koperasi">
        </div>

        <div class="center-brand">
            <h1 class="main-title">SISTEM INFORMASI TERPADU</h1>
            <h2 class="sub-title">
                KOPERASI <span class="txt-merah">MERAH</span> <span class="txt-putih">PUTIH</span>
            </h2>
            <h2 class="sub-title2" style="font-size: 11px;">DESA NANGSRI</h2>
        </div>

        <div class="header-right">
            <a href="/dashboard" class="btn-home" id="home">
                <i class="fas fa-home"></i>
            </a>
        </div>
    </div>

    <div style="height: 3px; background: #e63946; width: 100%;"></div>

    <div class="nav-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div id="default-status-text">
                <small class="text-muted-custom text-uppercase">
                    YOGATECHSOLUTION | CORE SYSTEM V1.0
                </small>
            </div>

            <div class="flex justify-center items-center {{request()->routeIs('home', 'logout') ? 'hidden' : ''}} hidden [&>a:hover]:bg-sky-400/40 [&>a:hover]:backdrop-blur-2xl" id="simpan-pinjam-nav">
                <span class="ml-2">|</span>
                <a href="#" class="tab-nav mx-4 px-2 py-0.5 rounded-md text-sm text-black font-medium transition-colors duration-200" id="tab-dashboard">
                    <i class="fas fa-chart-line mr-2"></i>
                    DASHBOARD
                </a>
                <a href="{{ route('pinjaman.index') }}" class="tab-nav mx-4 px-2 py-0.5 rounded-md text-sm text-black font-medium transition-colors duration-200" id="tab-pinjaman">
                    <i class="fas fa-hand-holding-usd mr-2"></i>
                    PINJAMAN
                </a>
                <a href="{{ route('simpanan.index') }}" class="tab-nav mx-4 px-2 py-0.5 rounded-md text-sm text-black font-medium transition-colors duration-200" id="tab-simpanan">
                    <i class="fas fa-piggy-bank mr-2"></i>
                    SIMPANAN
                </a>
                <a href="{{ route('laporan.index') }}" class="tab-nav mx-4 px-2 py-0.5 rounded-md text-sm text-black font-medium transition-colors duration-200" id="tab-laporan">
                    <i class="fas fa-chart-bar mr-2"></i>
                    LAPORAN
                </a>
            </div>


            <div id="admin-nav-menu" class="d-none">
                <div class="dropdown">
                    <a class="nav-link-admin dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Otorisasi User
                    </a>

                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="/admin/master-user">Master User</a></li>
                        <li><a class="dropdown-item" href="/admin/ganti-password">Ganti Password</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <i class="far fa-calendar-alt me-2"></i>
            <span id="txtTanggal"></span>
            
            <div style="width: 1px; height: 18px; background: rgba(0, 0, 0, 0.2); margin: 0 15px;"></div>
            
            <a href="/logout" id="logout">
                <button class="btn-logout">LOGOUT</button>
            </a>
        </div>
    </div>

    <div class="container-fluid py-4">
        @yield('content')
    </div>

    <!-- Modals Container -->
    <div id="modals-container"></div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function updateClock() {
            const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const d = new Date();
            const output = hari[d.getDay()] + ", " + d.getDate() + " " + bulan[d.getMonth()] + " " + d.getFullYear() + " | " + 
                           d.getHours().toString().padStart(2, '0') + ":" + d.getMinutes().toString().padStart(2, '0') + " WIB";
            document.getElementById('txtTanggal').innerHTML = output;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Fungsi Ganti Menu (Dipanggil saat ADM. SISTEM di klik)
        function showAdminMenu() {
            const textDefault = document.getElementById('default-status-text');
            const menuAdmin = document.getElementById('admin-nav-menu');

            if(textDefault && menuAdmin) {
                textDefault.classList.add('d-none');
                menuAdmin.classList.remove('d-none');
                menuAdmin.classList.add('d-block');
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('scripts')
</body>
</html>