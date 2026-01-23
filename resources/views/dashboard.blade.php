@extends('layouts.master')

@section('content')
<style>
    .menu-wrapper {
        /* Mempersempit lebar maksimal agar tombol lebih merapat satu sama lain */
        max-width: 820px; 
        margin: 10px auto;
        padding: 0 10px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px; /* Jarak antar tombol diperkecil */
        justify-items: center;
    }

    .management-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        justify-items: center;
        margin-top: 10px;
    }

    /* Perbaikan Garis Merah Kantor Koperasi */
    .divider-wrapper {
        grid-column: span 4;
        display: flex;
        align-items: center;
        width: 100%; /* Memastikan wrapper mengambil lebar penuh */
        margin: 20px 0 15px 0;
    }

    /* Garis Kiri dan Kanan */
    .divider-line {
        flex: 1;
        height: 2px;
        background-color: #ae1515; /* Warna merah sesuai tema */
    }

    .divider-text {
        padding: 0 20px;
        color: #ae1515;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 13px;
        white-space: nowrap; /* Agar teks tidak turun ke bawah */
    }

    .cyber-card {
        background: rgba(174, 21, 21, 0.99); 
        border: 1px solid rgba(206, 159, 159, 0.2);
        border-radius: 10px;
        padding: 10px 5px;
        transition: all 0.3s ease;
        text-decoration: none !important;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        
        /* Ukuran Persegi Panjang Rapat */
        width: 185px; 
        height: 110px; 
        
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .cyber-card:hover {
        transform: translateY(-5px);
        background: rgba(190, 0, 0, 1); 
        box-shadow: 0 8px 25px rgba(139, 0, 0, 0.5);
        border-color: rgba(255,255,255,0.5);
    }

    /* Ukuran khusus untuk baris bawah (5 kolom) agar tetap rapat */
    .management-grid .cyber-card {
        width: 155px; 
        height: 100px;
    }

    .icon-wrapper {
        width: 35px; height: 35px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; margin-bottom: 6px;
    }

    .card-title { 
        font-size: 10px; font-weight: 800; color: #ffffff; 
        margin-bottom: 2px; text-transform: uppercase; 
        text-align: center; line-height: 1.1;
    }
    
    .card-desc { 
        font-size: 8px; color: #ffffff; 
        text-align: center; opacity: 0.7; line-height: 1;
    }
</style>

<div class="menu-wrapper">
    <div class="menu-grid">
        @php
        $unitMenus = [
            ['icon' => 'fa-store', 'title' => 'Gerai', 'color' => 'linear-gradient(135deg, #06b6d4, #0891b2)', 'link' => route('kasir.index'), 'sub' => 'Point of Sales'],
            ['icon' => 'fa-pills', 'title' => 'Apotik', 'color' => 'linear-gradient(135deg, #0ea5e9, #2563eb)', 'link' => '#', 'sub' => 'Pharmacy'],
            ['icon' => 'fa-clinic-medical', 'title' => 'Klinik', 'color' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)', 'link' => '#', 'sub' => 'Medical Services'],
            ['icon' => 'fa-hand-holding-dollar', 'title' => 'Simpan Pinjam', 'color' => 'linear-gradient(135deg, #10b981, #059669)', 'link' => route('simpanpinjam.index'), 'sub' => 'Credit & Saving'],
            
            ['icon' => 'fa-warehouse', 'title' => 'Gudang Gerai', 'color' => 'linear-gradient(135deg, #6366f1, #4338ca)', 'link' => route('gudang.index'), 'sub' => 'Retail Stock'],
            ['icon' => 'fa-capsules', 'title' => 'Gudang Apotik', 'color' => 'linear-gradient(135deg, #ec4899, #be185d)', 'link' => '#', 'sub' => 'Medicine Stock'],
            ['icon' => 'fa-truck-ramp-box', 'title' => 'Gudang Distribusi', 'color' => 'linear-gradient(135deg, #f59e0b, #d97706)', 'link' => '#', 'sub' => 'Central Hub'],
            ['icon' => 'fa-users', 'title' => 'Data Anggota', 'color' => 'linear-gradient(135deg, #4b5563, #1f2937)', 'link' => route('member.index'), 'sub' => 'Membership']
        ];
        @endphp

        @foreach($unitMenus as $menu)
        <a href="{{ $menu['link'] }}" class="cyber-card shadow">
            <div class="icon-wrapper text-white" style="background: {{ $menu['color'] }};">
                <i class="fas {{ $menu['icon'] }}"></i>
            </div>
            <div class="card-title">{{ $menu['title'] }}</div>
            <div class="card-desc">{{ $menu['sub'] }}</div>
        </a>
        @endforeach

        {{-- Perbaikan Struktur Garis Pemisah --}}
        <div class="divider-wrapper">
            <div class="divider-line"></div>
            <span class="divider-text">Kantor Koperasi</span>
            <div class="divider-line"></div>
        </div>
    </div>

    <div class="management-grid">
        @php
        $kantorMenus = [
            ['icon' => 'fa-chart-line', 'title' => 'Dashboard', 'color' => 'linear-gradient(135deg, #64748b, #334155)', 'link' => '#', 'sub' => 'Stats'],
            ['icon' => 'fa-calculator', 'title' => 'Akuntansi', 'color' => 'linear-gradient(135deg, #64748b, #334155)', 'link' => '#', 'sub' => 'Ledger'],
            ['icon' => 'fa-wallet', 'title' => 'Keuangan', 'color' => 'linear-gradient(135deg, #64748b, #334155)', 'link' => '#', 'sub' => 'Flow'],
            ['icon' => 'fa-file-invoice-dollar', 'title' => 'Laporan', 'color' => 'linear-gradient(135deg, #64748b, #334155)', 'link' => '#', 'sub' => 'Reporting'],
            ['icon' => 'fa-user-gear', 'title' => 'Adm. Sistem', 'color' => 'linear-gradient(135deg, #475569, #1e293b)', 'link' => route('user.index'), 'sub' => 'Control', 'id' => 'btn-adm-sistem']
        ];
        @endphp

        @foreach($kantorMenus as $menu)
        <a href="{{ $menu['link'] }}" id="{{ $menu['id'] ?? '' }}" class="cyber-card shadow" @if(($menu['id'] ?? '') == 'btn-adm-sistem') onclick="showAdminMenu()" @endif>
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