<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifikasi Data Anggota | Core System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed;
            background-size: cover; 
            margin: 0; 
            min-height: 100vh; 
            font-family: 'Inter', sans-serif;
            padding: 40px 20px;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px; 
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            max-width: 600px; 
            margin: 0 auto;
        }

        /* Judul Form Hitam Pekat */
        .form-title {
            color: #000000 !important; 
            font-weight: 900; 
            text-transform: uppercase;
            letter-spacing: 1px; 
            border-left: 5px solid #ffc107; /* Aksen kuning untuk Edit */
            padding-left: 15px; 
            margin-bottom: 30px;
        }

        .form-label { 
            color: #1d3557; 
            font-weight: 800; 
            font-size: 12px; 
            text-transform: uppercase; 
        }

        /* Warna Teks Isian Hitam */
        .form-control {
            background: rgba(255, 255, 255, 0.9); 
            border: 1px solid #ced4da;
            color: #000000 !important; 
            border-radius: 8px; 
            padding: 12px;
            font-weight: 600;
        }

        .form-control:focus {
            background: #ffffff;
            color: #000000 !important;
            border-color: #ffc107; 
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        }

        .btn-cyber {
            font-weight: bold; 
            border-radius: 8px; 
            padding: 15px;
            text-transform: uppercase; 
            transition: 0.3s; 
            border: none; 
            width: 100%;
        }

        /* Tombol Update warna Kuning sesuai gambar asal */
        .btn-update { 
            background: #ffc107; 
            color: #000 !important; 
            margin-top: 20px; 
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.4);
        }

        /* Tombol Batal arah ke Data Anggota */
        .btn-back { 
            background: #6c757d; 
            color: white !important; 
            text-decoration: none; 
            display: block; 
            text-align: center; 
            margin-top: 10px; 
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-cyber:hover { 
            transform: translateY(-2px); 
            opacity: 0.95; 
        }
    </style>
</head>
<body>

<div class="glass-panel">
    <h4 class="form-title">MODIFIKASI DATA ANGGOTA</h4>

    <form action="/update/{{ $member->id }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">NIK (Non-Editable)</label>
            <input type="text" name="nik" class="form-control" value="{{ $member->nik }}" readonly style="background: rgba(0,0,0,0.05);">
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap Anggota</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ $member->nama_lengkap }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp / HP</label>
            <input type="text" name="nomor_hp" class="form-control" value="{{ $member->nomor_hp }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Lengkap Domisili</label>
            <textarea name="alamat" class="form-control" rows="3">{{ $member->alamat }}</textarea>
        </div>

        <button type="submit" class="btn-cyber btn-update">
            <i class="fas fa-sync-alt me-2"></i> UPDATE PERUBAHAN DATA
        </button>
        
        {{-- Link Batal diarahkan ke /anggota --}}
        <a href="/anggota" class="btn-cyber btn-back">
            <i class="fas fa-times me-2"></i> BATAL / KEMBALI
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>