@extends('layouts.master')

@section('content')
<style>
    .menu-wrapper {
        max-width: 720px;
        margin: 30px auto;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .cyber-card {
        /* Background Merah Tua Transparan dengan efek Glassmorphism */
        background: rgba(174, 21, 21, 0.99); 
        border: 1px solid rgba(206, 159, 159, 0.2);
        border-radius: 12px;
        padding: 15px 5px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none !important;
        position: relative;
        overflow: hidden;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 115px; /* Tetap Kompak */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    /* Shine Effect diubah ke warna putih sutra agar kontras dengan merah tua */
    .cyber-card::before {
        content: ''; 
        position: absolute; 
        top: 0; 
        left: -150%;
        width: 100%; 
        height: 100%;
        transition: 0.6s;
    }

    .cyber-card:hover::before { 
        left: 150%; 
    }

    .cyber-card:hover {
        transform: translateY(-8px) scale(1.02);
        /* Saat di-hover merahnya menjadi lebih terang/solid */
        background: rgba(180, 0, 0, 0.9); 
        border-color: #ffffff;
        /* Shadow merah menyala */
        box-shadow: 0 15px 30px rgba(139, 0, 0, 0.6), 0 0 20px rgba(255, 255, 255, 0.2);
    }
    .icon-wrapper {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 6px; /* Mepet */
        transition: 0.3s;
    }

    .card-title { 
        font-size: 10.5px; font-weight: 800; color: #ffffff; 
        margin-bottom: 2px; letter-spacing: 1px;
        text-transform: uppercase; line-height: 1.2;
    }
    
    .card-desc { 
        font-size: 8.5px; color: #ffffff; 
        text-align: center; opacity: 0.6; line-height: 1;
    }
</style>

<div class="menu-wrapper">
    <div class="menu-grid">
        @php
        // Struktur Menu 3x3 Sesuai Instruksi
        $menus = [
            ['icon' => 'fa-cash-register', 'title' => 'Kasir', 'color' => 'linear-gradient(135deg, #06b6d4, #0891b2)', 'link' => '#', 'sub' => 'Point of Sales'],
            ['icon' => 'fa-users', 'title' => 'Data Anggota', 'color' => 'linear-gradient(135deg, #0ea5e9, #2563eb)', 'link' => '/anggota', 'sub' => 'Membership'],
            ['icon' => 'fa-hand-holding-dollar', 'title' => 'Simpan Pinjam', 'color' => 'linear-gradient(135deg, #10b981, #059669)', 'link' => '#', 'sub' => 'Credit & Saving'],
            
            ['icon' => 'fa-calculator', 'title' => 'Akuntansi', 'color' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)', 'link' => '#', 'sub' => 'General Ledger'],
            ['icon' => 'fa-wallet', 'title' => 'Keuangan', 'color' => 'linear-gradient(135deg, #f59e0b, #d97706)', 'link' => '#', 'sub' => 'Financial Flow'],
            ['icon' => 'fa-warehouse', 'title' => 'Gudang', 'color' => 'linear-gradient(135deg, #6366f1, #4338ca)', 'link' => '#', 'sub' => 'Inventory Control'],
            
            ['icon' => 'fa-chart-line', 'title' => 'Dashboard', 'color' => 'linear-gradient(135deg, #ec4899, #be185d)', 'link' => '#', 'sub' => 'Statistic Chart'],
            ['icon' => 'fa-file-invoice-dollar', 'title' => 'Laporan', 'color' => 'linear-gradient(135deg, #ef4444, #dc2626)', 'link' => '#', 'sub' => 'Reporting System'],
            ['icon' => 'fa-user-gear', 'title' => 'Adm. Sistem', 'color' => 'linear-gradient(135deg, #475569, #1e293b)', 'link' => '#', 'sub' => 'Control Panel']
        ];
        @endphp

        @foreach($menus as $menu)
        <a href="{{ $menu['link'] }}" class="cyber-card shadow">
            <div class="icon-wrapper text-white" style="background: {{ $menu['color'] }};">
                <i class="fas {{ $menu['icon'] }}"></i>
            </div>
            <div class="card-title">{{ $menu['title'] }}</div>
            <div class="card-desc">{{ $menu['sub'] }}</div>
        </a>
        @endforeach
    </div>
</div>
@endsection