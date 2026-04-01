<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>direção</title>
    <link rel="stylesheet" href="{{ asset('../css/direcao.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')

    <h1>Olá Direção!</h1>
    <form method="POST" action="/logout">
    @csrf
    <button type="submit" class="logout">Logout</button>

    <h2>HAEs Submetidas</h2>
    <a href="/direcao/relatores" class="btn-results">Ver Relatores</a>

    @include('components.exibir-hae')
    <a href="/resultados-dir" class="btn-results">Ver Resultados</a>

    <h2>Quantidade de HAEs</h2>

</body>
</html>