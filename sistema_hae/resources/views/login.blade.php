<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('../css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    <div class="login-container">

        <h2>Login de Usuário</h2>

        <form method="POST" action="/login/{{ $tipo }}">
            @csrf

            <input type="text" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Senha">

            <button type="submit">Entrar</button>
        </form>

        <a href="/">voltar a página inicial</a>

    </div>
</body>
</html>