<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Toolkit - Edit Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .form-card { 
            max-width: 550px; margin: 50px auto; padding: 25px; 
            background: #fff; border: 1px solid #dee2e6; box-shadow: 0 4px 10px rgba(0,0,0,0.08); border-radius: 8px;
        }
        .header-title { font-size: 14px; font-weight: bold; border-bottom: 2px solid #ffc107; margin-bottom: 20px; display: inline-block; text-transform: uppercase; }
        label { font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .form-control-sm { border-radius: 4px; font-size: 13px; background-color: #fafafa; }
        .btn-save { font-size: 11px; font-weight: bold; text-transform: uppercase; padding: 8px 20px; }
    </style>
</head>
<body>
    <div class="form-card">
        <h5 class="header-title">Modifikasi Data Anggota</h5>
        <form action="/update/{{ $member->id }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nomor Induk Kependudukan</label>
                <input type="text" name="nik" class="form-control form-control-sm" value="{{ $member->nik }}" required>
            </div>
            <div class="mb-3">
                <label>Nama Lengkap Pasien/Anggota</label>
                <input type="text" name="nama_lengkap" class="form-control form-control-sm" value="{{ $member->nama_lengkap }}" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label>Kontak WhatsApp</label>
                    <input type="text" name="nomor_hp" class="form-control form-control-sm" value="{{ $member->nomor_hp }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label>Alamat Domisili</label>
                <textarea name="alamat" class="form-control form-control-sm" rows="3">{{ $member->alamat }}</textarea>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                <a href="/" class="btn btn-light btn-sm text-secondary small fw-bold">CANCEL</a>
                <button type="submit" class="btn btn-warning btn-save shadow-sm">Update Records</button>
            </div>
        </form>
    </div>
</body>
</html>