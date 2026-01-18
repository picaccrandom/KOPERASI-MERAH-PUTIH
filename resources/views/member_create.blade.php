<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota Baru | Core System</title>
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

        /* PERBAIKAN: Judul Form diubah menjadi HITAM PEKAT agar terlihat jelas */
        .form-title {
            color: #000000 !important; 
            font-weight: 900; 
            text-transform: uppercase;
            letter-spacing: 1px; 
            border-left: 5px solid #e63946;
            padding-left: 15px; 
            margin-bottom: 30px;
            text-shadow: none; /* Hilangkan shadow putih agar tidak blur */
        }

        /* Label input juga dibuat hitam/gelap agar kontras */
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
            border-color: #e63946; 
            box-shadow: 0 0 0 0.25rem rgba(230, 57, 70, 0.25);
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

        .btn-save { 
            background: #e63946; 
            color: white !important; 
            margin-top: 20px; 
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.4);
        }

        .btn-back { 
            background: #1d3557; 
            color: white !important; 
            text-decoration: none; 
            display: block; 
            text-align: center; 
            margin-top: 10px; 
            box-shadow: 0 4px 15px rgba(29, 53, 87, 0.4);
        }

        .btn-cyber:hover { 
            transform: translateY(-2px); 
            opacity: 0.95; 
        }
    </style>
</head>
<body>

<div class="glass-panel">
    <h4 class="form-title">REGISTRASI ANGGOTA BARU</h4>

    <form action="/simpan" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nomor Induk Kependudukan (NIK)</label>
            <input type="text" name="nik" class="form-control" placeholder="Input 16 digit NIK" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap Sesuai KTP</label>
            <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor WhatsApp Aktif</label>
            <input type="text" name="nomor_hp" class="form-control" placeholder="08xxxxxxxxxx">
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Domisili</label>
            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat Lengkap"></textarea>
        </div>

        <button type="submit" class="btn-cyber btn-save">
            <i class="fas fa-save me-2"></i> SIMPAN DATA ANGGOTA
        </button>
        
        <a href="/anggota" class="btn-cyber btn-back">
            <i class="fas fa-arrow-left me-2"></i> BATAL / KEMBALI
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>