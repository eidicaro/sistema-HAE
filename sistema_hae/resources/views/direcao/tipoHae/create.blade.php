<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Tipo de HAE</title>

    <link rel="stylesheet" href="{{ asset('css/tipos-hae.css') }}">
</head>
<body>

<div class="th-container">

    <div class="th-header">
        <h1>Novo Tipo de HAE</h1>
    </div>

    <div class="th-card">

        <form action="{{ route('direcao.tipos-hae.store') }}" method="POST">
            @csrf

            @include('direcao.tipoHae._form')

            <div class="th-actions-bottom">
                <button type="submit" class="th-btn">Salvar</button>

                <a href="{{ route('direcao.tipos-hae.index') }}" class="th-btn th-btn-outline">
                    Cancelar
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>