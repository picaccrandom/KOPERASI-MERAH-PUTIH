<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Koperasi Merah Putih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url("{{ asset('img/background-koperasi.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px; padding: 40px; width: 100%; max-width: 400px; text-align: center;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        .btn-login {
            background: #e63946; color: white; border: none; width: 100%; padding: 12px;
            border-radius: 10px; font-weight: bold; text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="{{ asset('img/logo-koperasi.png') }}" style="height: 80px; margin-bottom: 20px;">
        <h2 style="color: white; font-weight: 850; font-size: 18px; letter-spacing: 2px;">CORE SYSTEM V1.0</h2>
        
        <form action="/login-proses" method="POST">
            @csrf
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
            <button type="submit" class="btn-login">MASUK SISTEM</button>
            
            @if(session('error'))
                <div class="mt-3 text-warning fw-bold" style="font-size: 12px;">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>
</body>
</html>