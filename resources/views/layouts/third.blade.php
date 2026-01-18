<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Simpan Pinjam - Koperasi Merah Putih')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Tema Merah Putih Koperasi */
        :root {
            --primary-red: #dc2626;
            --primary-red-dark: #b91c1c;
            --primary-red-light: #fef2f2;
        }
        
        .tab-active {
            background-color: var(--primary-red-light);
            color: var(--primary-red);
            border-top: 3px solid var(--primary-red);
        }
        
        /* Table Styles seperti Master Gudang */
        .table-gudang {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 0.875rem;
        }
        
        .table-gudang th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .table-gudang td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table-gudang tr:hover {
            background-color: #f8fafc;
        }
        
        .table-gudang tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-aktif {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-selesai {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .status-belum {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Atas -->
    <header class="bg-white shadow">
        <div class="border-b border-gray-200">
            <!-- Info Sistem -->
            <div class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold">SISTEM SIMPAN PINJAM</h1>
                        <p class="text-sm opacity-90">KOPERASI MERAH PUTIH - DESA MANGSURI</p>
                    </div>
                    <div class="text-right text-sm">
                        <p>YOGATECHSOLUTION | V1.0</p>
                        <p>{{ date('l, d F Y') }} | {{ date('H:i') }} WIB</p>
                        <a href="/dashboard" class="hover:underline">LOGOUT</a>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Tabs -->
            <nav class="px-6 py-2 bg-white">
                <div class="flex space-x-1">
                    <a href="{{ route('simpanpinjam.index') }}" class="tab-nav px-4 py-3 text-sm font-medium transition-colors duration-200" id="tab-dashboard">
                        <i class="fas fa-chart-line mr-2"></i>
                        DASHBOARD
                    </a>
                    <a href="{{ route('pinjaman.index') }}" class="tab-nav px-4 py-3 text-sm font-medium transition-colors duration-200" id="tab-pinjaman">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        PINJAMAN
                    </a>
                    <a href="{{ route('simpanan.index') }}" class="tab-nav px-4 py-3 text-sm font-medium transition-colors duration-200" id="tab-simpanan">
                        <i class="fas fa-piggy-bank mr-2"></i>
                        SIMPANAN
                    </a>
                    <a href="{{ route('laporan.index') }}" class="tab-nav px-4 py-3 text-sm font-medium transition-colors duration-200" id="tab-laporan">
                        <i class="fas fa-chart-bar mr-2"></i>
                        LAPORAN
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-6 py-6">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

    <!-- Modals Container -->
    <div id="modals-container"></div>

    <script>
        // Tailwind config
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        red: {
                            600: '#dc2626',
                            700: '#b91c1c',
                            50: '#fef2f2'
                        }
                    }
                }
            }
        }
        
        // Tab Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Set active tab berdasarkan URL
            const currentPath = window.location.pathname;
            const tabs = document.querySelectorAll('.tab-nav');
            
            tabs.forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('text-gray-600', 'hover:text-red-600', 'hover:bg-red-50');
                
                if (currentPath.includes(tab.id.replace('tab-', ''))) {
                    tab.classList.remove('text-gray-600', 'hover:text-red-600', 'hover:bg-red-50');
                    tab.classList.add('tab-active');
                }
            });
        });
        
        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
        }
        
        // Show alert
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `fixed top-20 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700'}`;
            alert.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    </script>
    
    @yield('scripts')
</body>
</html>