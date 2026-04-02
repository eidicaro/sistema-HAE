<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>resultados-dir</title>
    <link rel="stylesheet" href="{{ asset('../css/direcao.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')

    <a href="/direcao" class="btn-voltar">Voltar</a>

    <h2>HAEs Submetidas</h2>

    @include('components.exibir-hae2')

    <h2>Estatísticas</h2>
</body>
</html>