@extends('layouts.third')

@section('title', 'Laporan - Koperasi Merah Putih')

@section('content')
<!-- Filter Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Filter Laporan</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
            <select id="jenisLaporan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <option value="pinjaman">Pinjaman</option>
                <option value="simpanan">Simpanan</option>
                <option value="angsuran">Angsuran</option>
                <option value="keuangan">Keuangan</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
            <input type="date" id="dariTanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
            <input type="date" id="sampaiTanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
        </div>
        <div class="flex items-end">
            <button onclick="generateLaporan()" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-lg shadow p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Total Pinjaman Aktif</p>
                <p class="text-2xl font-bold" id="total-pinjaman-aktif">Rp 1.100.000</p>
            </div>
            <i class="fas fa-hand-holding-usd text-3xl opacity-80"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Total Simpanan</p>
                <p class="text-2xl font-bold" id="total-simpanan">Rp 500.000</p>
            </div>
            <i class="fas fa-piggy-bank text-3xl opacity-80"></i>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Total Angsuran Bulan Ini</p>
                <p class="text-2xl font-bold" id="total-angsuran">Rp 57.000</p>
            </div>
            <i class="fas fa-calendar-check text-3xl opacity-80"></i>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Pinjaman</h3>
        <canvas id="pinjamanChart" height="250"></canvas>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Simpanan</h3>
        <canvas id="simpananChart" height="250"></canvas>
    </div>
</div>

<!-- Report Tables -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Laporan Detail</h3>
            <p class="text-sm text-gray-600">Data berdasarkan filter yang dipilih</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center">
                <i class="fas fa-file-excel mr-2"></i> Excel
            </button>
            <button onclick="exportToPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> PDF
            </button>
            <button onclick="printLaporan()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>
    </div>
    
    <div class="p-6">
        <div id="laporanContent">
            <!-- Laporan will be loaded here -->
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-4 opacity-50"></i>
                <p>Pilih jenis laporan dan klik "Filter" untuk menampilkan data</p>
            </div>
        </div>
    </div>
</div>

<!-- Summary Report -->
<div class="mt-6 bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Rekapitulasi Keuangan</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Total Pemasukan</p>
            <p class="text-xl font-bold text-green-600" id="total-pemasukan">Rp 1.157.000</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Total Pengeluaran</p>
            <p class="text-xl font-bold text-red-600" id="total-pengeluaran">Rp 500.000</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Saldo Kas</p>
            <p class="text-xl font-bold text-blue-600" id="saldo-kas">Rp 657.000</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-sm text-gray-600">Jumlah Anggota</p>
            <p class="text-xl font-bold text-purple-600" id="jumlah-anggota">2</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Data for reports
const laporanData = {
    pinjaman: [
        { bulan: 'Jan', jumlah: 2, total: 1100000 },
        { bulan: 'Feb', jumlah: 1, total: 500000 },
        { bulan: 'Mar', jumlah: 3, total: 2000000 },
        { bulan: 'Apr', jumlah: 2, total: 1500000 },
        { bulan: 'Mei', jumlah: 4, total: 3000000 },
        { bulan: 'Jun', jumlah: 2, total: 1200000 }
    ],
    simpanan: [
        { kategori: 'Wajib', jumlah: 500000, persentase: 50 },
        { kategori: 'Pokok', jumlah: 300000, persentase: 30 },
        { kategori: 'Sukarela', jumlah: 200000, persentase: 20 }
    ],
    angsuran: [
        { bulan: 'Jan', lunas: 5, belum: 2 },
        { bulan: 'Feb', lunas: 8, belum: 1 },
        { bulan: 'Mar', lunas: 10, belum: 0 },
        { bulan: 'Apr', lunas: 7, belum: 3 },
        { bulan: 'Mei', lunas: 12, belum: 2 },
        { bulan: 'Jun', lunas: 9, belum: 1 }
    ]
};

// Initialize charts
let pinjamanChart, simpananChart;

document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    updateStats();
    generateLaporan();
});

function initializeCharts() {
    // Pinjaman Chart
    const pinjamanCtx = document.getElementById('pinjamanChart').getContext('2d');
    pinjamanChart = new Chart(pinjamanCtx, {
        type: 'bar',
        data: {
            labels: laporanData.pinjaman.map(item => item.bulan),
            datasets: [{
                label: 'Jumlah Pinjaman',
                data: laporanData.pinjaman.map(item => item.jumlah),
                backgroundColor: 'rgba(220, 38, 38, 0.7)',
                borderColor: 'rgba(220, 38, 38, 1)',
                borderWidth: 1
            }, {
                label: 'Total Pinjaman (Rp)',
                data: laporanData.pinjaman.map(item => item.total / 100000),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pinjaman'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Total (Rp x 100k)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });

    // Simpanan Chart
    const simpananCtx = document.getElementById('simpananChart').getContext('2d');
    simpananChart = new Chart(simpananCtx, {
        type: 'doughnut',
        data: {
            labels: laporanData.simpanan.map(item => item.kategori),
            datasets: [{
                data: laporanData.simpanan.map(item => item.persentase),
                backgroundColor: [
                    'rgba(220, 38, 38, 0.7)',
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(234, 179, 8, 0.7)'
                ],
                borderColor: [
                    'rgba(220, 38, 38, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(234, 179, 8, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function updateStats() {
    document.getElementById('total-pinjaman-aktif').textContent = formatCurrency(1100000);
    document.getElementById('total-simpanan').textContent = formatCurrency(500000);
    document.getElementById('total-angsuran').textContent = formatCurrency(57000);
    document.getElementById('total-pemasukan').textContent = formatCurrency(1157000);
    document.getElementById('total-pengeluaran').textContent = formatCurrency(500000);
    document.getElementById('saldo-kas').textContent = formatCurrency(657000);
    document.getElementById('jumlah-anggota').textContent = '2';
}

function formatCurrency(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function generateLaporan() {
    const jenis = document.getElementById('jenisLaporan').value;
    const dari = document.getElementById('dariTanggal').value;
    const sampai = document.getElementById('sampaiTanggal').value;
    
    let content = '';
    
    switch(jenis) {
        case 'pinjaman':
            content = generatePinjamanLaporan();
            break;
        case 'simpanan':
            content = generateSimpananLaporan();
            break;
        case 'angsuran':
            content = generateAngsuranLaporan();
            break;
        case 'keuangan':
            content = generateKeuanganLaporan();
            break;
    }
    
    document.getElementById('laporanContent').innerHTML = content;
}

function generatePinjamanLaporan() {
    return `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pinjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${laporanData.pinjaman.map(item => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">${item.bulan}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item.jumlah} pinjaman</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-red-600">${formatCurrency(item.total)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(item.total / item.jumlah)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${item.jumlah > 2 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${item.jumlah > 2 ? 'Tinggi' : 'Normal'}
                                </span>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function generateSimpananLaporan() {
    return `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rata-rata per Anggota</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${laporanData.simpanan.map((item, index) => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${index === 0 ? 'bg-blue-100 text-blue-800' : index === 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                    ${item.kategori}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-red-600">${formatCurrency(item.jumlah)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item.persentase}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(item.jumlah / (index + 1))}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function generateAngsuranLaporan() {
    return `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lunas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Belum Lunas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Persentase Lunas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${laporanData.angsuran.map(item => {
                        const total = item.lunas + item.belum;
                        const persentase = ((item.lunas / total) * 100).toFixed(1);
                        return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">${item.bulan}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    ${item.lunas}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    ${item.belum}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold">${total}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: ${persentase}%"></div>
                                </div>
                                <span class="text-xs text-gray-600 mt-1">${persentase}%</span>
                            </td>
                        </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function generateKeuanganLaporan() {
    return `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feb</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apr</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-green-600">Pemasukan</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(500000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(450000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(600000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(550000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(2100000)}</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-red-600">Pengeluaran</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(200000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(250000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(300000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${formatCurrency(180000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(930000)}</td>
                    </tr>
                    <tr class="hover:bg-gray-50 bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-blue-600">Saldo</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(300000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(200000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(300000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold">${formatCurrency(370000)}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-blue-600">${formatCurrency(1170000)}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
}

function exportToExcel() {
    showAlert('Fitur export Excel dalam pengembangan', 'info');
}

function exportToPDF() {
    showAlert('Fitur export PDF dalam pengembangan', 'info');
}

function printLaporan() {
    window.print();
}

function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${type === 'error' ? 'bg-red-100 border-red-400 text-red-700' : type === 'info' ? 'bg-blue-100 border-blue-400 text-blue-700' : 'bg-green-100 border-green-400 text-green-700'}`;
    alert.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : type === 'info' ? 'fa-info-circle' : 'fa-check-circle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 3000);
}
</script>
@endsection