@extends('layouts.master')

@section('content')
<style>
    .form-glass {
        max-width: 500px;
        margin: 40px auto;
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .form-label { font-size: 12px; font-weight: 700; color: #1d3557; }
    .btn-simpan { background: #1d3557; color: white; font-weight: 800; width: 100%; padding: 10px; border-radius: 8px; transition: 0.3s; }
    .btn-simpan:hover { background: #e63946; }
</style>

<div class="container">
    <div class="form-glass">
        <div class="text-center mb-4">
            <i class="fas fa-key fa-3x text-danger mb-2"></i>
            <h5 class="fw-bold text-dark">GANTI PASSWORD SYSTEM</h5>
        </div>

        @if (session('success'))
            <div class="alert alert-success py-2 small border-0 shadow-sm mb-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0 shadow-sm mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update.proses') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-uppercase">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-control form-control-sm border-dark shadow-none" required>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label text-uppercase">Password Baru</label>
                <input type="password" name="new_password" class="form-control form-control-sm border-dark shadow-none" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-uppercase">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-control form-control-sm border-dark shadow-none" required>
            </div>

            <button type="submit" class="btn-simpan border-0 shadow text-uppercase">
                <i class="fas fa-save me-2"></i> Update Password Sekarang
            </button>
        </form>
    </div>
</div>
@endsection