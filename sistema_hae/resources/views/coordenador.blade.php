<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coordenador</title>
    <link rel="stylesheet" href="{{ asset('../css/coordenador.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')

    <h1>Olá Coordenador!</h1>
    <form method="POST" action="/logout">
    @csrf
    <button type="submit">Logout</button>

    
    <h2>HAEs Submetidas</h2>

    @include('components.exibir-hae')
    
</body>
</html>