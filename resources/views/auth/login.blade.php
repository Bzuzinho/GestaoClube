<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessão - BSCN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #043277, #ffffff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
        }
        .login-card h2 {
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
            color: #043277;
        }
        .login-card .form-control {
            border-radius: 6px;
        }
        .login-card button {
            width: 100%;
            background-color: #043277;
            border: none;
        }
        .login-card button:hover {
            background-color: #032b64;
        }
        .login-logo{ display:flex; justify-content:center; margin-bottom:20px; }

        .login-logo img{ height:48px; width:auto; display:block; object-fit:contain; }

        .forgot-password {
            text-align: right;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 15px;
        }
        .forgot-password a {
            color: #043277;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo-clube.png') }}" alt="BSCN">
        </div>
        <h2>Iniciar Sessão</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Palavra-passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Esqueci-me da palavra-passe</a>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Manter Sessão Iniciada</label>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>
</body>
</html>
