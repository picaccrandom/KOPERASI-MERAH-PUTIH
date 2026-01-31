@extends('layouts.master')
@section('content')
<div class="min-h-screen bg-cover bg-fixed" style="background-image: url('{{ asset('img/background-klinik.png') }}');">
    <div class="bg-blue-900/10 backdrop-blur-md min-h-screen p-10">
        <div class="bg-white/95 rounded-2xl shadow-2xl mx-auto max-w-4xl p-12 border border-blue-100">
            <div class="mb-8 border-l-8 border-blue-600 pl-4 uppercase font-bold text-slate-800">
                <h1 class="text-3xl">Pendaftaran <span class="text-blue-600">Pasien Baru</span></h1>
            </div>
            <form action="{{ route('klinik.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Pilih Pasien</label>
                    <select name="member_id" required class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="">-- Cari NIK atau Nama --</option>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}">{{ $m->nik }} - {{ $m->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Keluhan Utama</label>
                        <textarea name="keluhan" required rows="3" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Tensi Darah</label>
                        <input type="text" name="tensi" class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 outline-none" placeholder="120/80">
                    </div>
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('klinik.index') }}" class="px-8 py-3 bg-slate-500 text-white rounded-xl font-bold uppercase">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 uppercase">Daftarkan Pasien</button>
                </div>

                @if(session('error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-6 font-bold shadow-lg animate-bounce">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-orange-500 text-white p-4 rounded-lg mb-6 shadow-lg">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            </form>
        </div>
    </div>
</div>
@endsection
