<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('../css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/modal-hae.css') }}">
</head>
<body>
    <div class="login-container">

        <h2>{{ ucfirst($tipo) }}</h2>

        <form method="POST">
            @csrf

            @if($tipo == 'direcao')
                <input type="text" name="nome" placeholder="Nome">
            @else
                <input type="text" name="cpf" placeholder="CPF">
            @endif

            <input type="password" name="senha" placeholder="Senha">

            <button type="submit">Entrar</button>
        </form>

    </div>
</body>
</html>