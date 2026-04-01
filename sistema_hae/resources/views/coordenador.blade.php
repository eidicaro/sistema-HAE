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
    <form method="POST" action="/logout" >
    @csrf
    <button type="submit" class="logout">Logout</button>

    
    <h2>HAEs Submetidas</h2>

    @include('components.exibir-hae')

    <h2>HAEs para Parecer</h2>

<div class="relator">
    <div class="hae-list">
        @forelse($haesRelator as $hae)
            <a href="/hae/{{ $hae->id }}" class="hae-item">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </a>
        @empty
            <p>Nenhuma HAE para parecer</p>
        @endforelse
    </div>
</div>
    
</body>
</html>