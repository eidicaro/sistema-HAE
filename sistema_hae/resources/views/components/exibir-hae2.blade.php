<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('../../css/exibir-hae.css') }}">
    <link rel="stylesheet" href="{{ asset('../../css/fonte.css') }}">
</head>
<body>
<div class="minhas-haes">

<!-- FINALIZADAS -->
<div class="hae-box">
    <div class="hae-header verde">
        Finalizadas
        @if(auth()->user()->role == 'direcao')
            <span class="qtd">({{ $finalizadas->count() }})</span>
        @endif
    </div>

    <div class="hae-list">
        @forelse($finalizadas as $hae)
            <a href="/hae/{{ $hae->id }}" class="hae-item verde">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </a>
        @empty
            <p>Nenhuma HAE finalizada</p>
        @endforelse
    </div>
</div>

<!-- RECUSADAS -->
<div class="hae-box">
    <div class="hae-header vermelho">
        Recusadas
        @if(auth()->user()->role == 'direcao')
            <span class="qtd">({{ $recusadas->count() }})</span>
        @endif
    </div>

    <div class="hae-list">
        @forelse($recusadas as $hae)
            <a href="/hae/{{ $hae->id }}" class="hae-item vermelho">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </a>
        @empty
            <p>Nenhuma HAE recusada</p>
        @endforelse
    </div>
</div>

</div>
</body>
</html>