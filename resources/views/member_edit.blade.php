<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Data Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .form-container { 
            max-width: 600px; margin: 50px auto; padding: 30px; 
            background: #fff; border-radius: 8px; border: 1px solid #dee2e6;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        label { font-size: 12px; font-weight: bold; color: #555; text-transform: uppercase; }
        .header-title { font-size: 16px; font-weight: bold; border-bottom: 2px solid #ffc107; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>
    <div class="form-container">
        <h5 class="header-title">MODIFIKASI DATA ANGGOTA</h5>
        <form action="/update/{{ $member->id }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control form-control-sm" value="{{ $member->nik }}" required>
            </div>
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control form-control-sm" value="{{ $member->nama_lengkap }}" required>
            </div>
            <div class="mb-3">
                <label>Nomor HP</label>
                <input type="text" name="nomor_hp" class="form-control form-control-sm" value="{{ $member->nomor_hp }}" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control form-control-sm" rows="3">{{ $member->alamat }}</textarea>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="/" class="btn btn-secondary btn-sm">Batal</a>
                <button type="submit" class="btn btn-warning btn-sm px-4 shadow-sm fw-bold">Update Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>