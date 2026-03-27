<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>professor</title>
    <link rel="stylesheet" href="{{ asset('../css/professor.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')

    <h1>Olá Professor!</h1>

    <div class="nova-hae">
        <label for="tipoHAE">Nova HAE</label>

        <select id="tipoHAE" class="select-hae">
            <option value="">Escolha o tipo de HAE</option>
            <option value="ensino">Ensino</option>
            <option value="pesquisa">Pesquisa</option>
            <option value="extensao">Extensão</option>
            <option value="gestao">Gestão</option>
        </select>
    </div>

    
    <h2>Minhas HAEs</h2>
    @include('components.exibir-hae')
    @include('components.exibir-hae2')
</body>
</html>