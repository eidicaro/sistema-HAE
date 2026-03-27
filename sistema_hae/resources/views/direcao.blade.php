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

    <h2>HAEs Submetidas</h2>

    @include('components.exibir-hae')
</body>
</html>