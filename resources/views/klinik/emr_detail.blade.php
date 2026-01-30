@extends('layouts.master')
@section('content')
<div class="min-h-screen bg-blue-900/10 p-10">
    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden border border-blue-100">
        <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-black uppercase tracking-widest">Riwayat Rekam Medis</h2>
                <p class="opacity-80 font-bold italic">{{ $data->no_registrasi }}</p>
            </div>
            <i class="fas fa-file-medical-alt text-4xl opacity-50"></i>
        </div>

        <div class="p-10 space-y-8">
            {{-- Identitas Pasien --}}
            <div class="grid grid-cols-2 gap-8 border-b border-blue-50 pb-6">
                <div><label class="text-xs font-black text-blue-600 uppercase">Nama Pasien</label>
                    <p class="text-xl font-bold text-slate-800">{{ $data->member->nama_lengkap }}</p></div>
                <div><label class="text-xs font-black text-blue-600 uppercase">Tanggal Periksa</label>
                    <p class="text-xl font-bold text-slate-800">{{ $data->updated_at->format('d M Y | H:i') }} WIB</p></div>
            </div>

            {{-- Hasil Diagnosa --}}
            <div class="bg-blue-50 p-6 rounded-2xl border-l-8 border-blue-600">
                <label class="text-xs font-black text-blue-600 uppercase block mb-2">Diagnosa Dokter</label>
                <p class="text-slate-700 font-bold leading-relaxed text-lg italic">
                    "{{ $data->rekamMedis->diagnosa ?? 'Data diagnosa tidak ditemukan' }}"
                </p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border-l-8 border-slate-400">
                <label class="text-xs font-black text-slate-500 uppercase block mb-2">Tindakan / Terapi</label>
                <p class="text-slate-700 font-bold leading-relaxed">
                    {{ $data->rekamMedis->tindakan ?? '-' }}
                </p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border-l-8 border-slate-400">
                <label class="text-xs font-black text-slate-500 uppercase block mb-2">Resep Obat</label>
                <p class="text-slate-700 font-bold leading-relaxed">
                    {{ $data->rekamMedis->obat->nama_obat ?? '-' }}
                </p>
            </div>

            <div class="flex justify-center pt-6">
                <a href="{{ route('klinik.index') }}" class="px-12 py-3 bg-blue-600 text-white rounded-xl font-black uppercase hover:bg-blue-700 shadow-xl transition-all">
                    Kembali ke Antrian
                </a>
            </div>
        </div>
    </div>
</div>
@endsection