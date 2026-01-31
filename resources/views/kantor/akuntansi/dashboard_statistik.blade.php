@extends('layouts.master')

@section('content')
<div class="p-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-end mb-10 border-l-8 border-blue-700 pl-6">
            <div>
                <h1 class="text-5xl font-black uppercase tracking-tighter text-slate-800">
                    Dashboard <span class="bg-blue-700 text-white px-3 rounded-lg shadow-lg">Statistik</span>
                </h1>
                <p class="text-slate-500 font-bold mt-2 italic text-sm">Visualisasi Performa Real-Time - Koperasi Merah Putih</p>
            </div>
            <a href="{{ route('akuntansi.index') }}" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg hover:bg-slate-900 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Co-A
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-8 rounded-3xl shadow-xl border-b-8 border-emerald-500">
                <h4 class="text-slate-400 font-black text-xs uppercase tracking-widest">Total Aset Saat Ini</h4>
                <p class="text-4xl font-black text-slate-800 mt-2">Rp {{ number_format($totalAset, 0, ',', '.') }}</p>
                <p class="text-emerald-500 text-[10px] font-bold mt-2 italic">*Total nilai seluruh akun aset (Kas, Bank, dll)</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-xl border-b-8 border-blue-500">
                <h4 class="text-slate-400 font-black text-xs uppercase tracking-widest">Pendapatan Bulan Ini</h4>
                <p class="text-4xl font-black text-slate-800 mt-2">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</p>
                <p class="text-blue-500 text-[10px] font-bold mt-2 italic">*Total akumulasi pendapatan unit toko & klinik</p>
            </div>
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-2xl border border-slate-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="font-black text-slate-700 uppercase tracking-widest text-lg">Tren Pendapatan vs Beban (7 Hari Terakhir)</h3>
                <span class="bg-slate-100 px-4 py-1 rounded-full text-[10px] font-black text-slate-500 uppercase">Live Data</span>
            </div>
            <div class="h-[400px]">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    // Gradient Backgrounds
    const incomeGradient = ctx.createLinearGradient(0, 0, 0, 400);
    incomeGradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    incomeGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    const expenseGradient = ctx.createLinearGradient(0, 0, 0, 400);
    expenseGradient.addColorStop(0, 'rgba(239, 68, 68, 0.4)');
    expenseGradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($days) !!},
            datasets: [
                {
                    label: 'PENDAPATAN',
                    data: {!! json_encode($incomeData) !!},
                    borderColor: '#10b981',
                    backgroundColor: incomeGradient,
                    borderWidth: 5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 6
                },
                {
                    label: 'PENGELUARAN (BEBAN)',
                    data: {!! json_encode($expenseData) !!},
                    borderColor: '#ef4444',
                    backgroundColor: expenseGradient,
                    borderWidth: 5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: 'Inter', weight: 'bold' }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection