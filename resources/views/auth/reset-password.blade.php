<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Palavra-passe - BSCN</title>
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
        .reset-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
        }
        .reset-card h2 {
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
            color: #043277;
        }
        .reset-card .form-control {
            border-radius: 6px;
        }
        .reset-card button {
            width: 100%;
            background-color: #043277;
            border: none;
        }
        .reset-card button:hover {
            background-color: #032b64;
        }
        .reset-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .reset-logo img {
            max-height: 70px;
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="reset-logo">
            <a href="{{ route('home') }}">
                <img src="/images/logo.png" alt="Logo BSCN">
            </a>
        </div>
        <h2>Nova Palavra-passe</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Nova palavra-passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar nova palavra-passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Palavra-passe</button>
        </form>
    </div>
</body>
</html>
