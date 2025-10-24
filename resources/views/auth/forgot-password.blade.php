<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Palavra-passe - BSCN</title>
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
        .back-to-login {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }
        .back-to-login a {
            color: #043277;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
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
        <h2>Recuperar Palavra-passe</h2>

        @if (session('status'))
            <div class="alert alert-success small">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Enviar link de recuperação</button>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login') }}">Voltar ao login</a>
        </div>
    </div>
</body>
</html>

