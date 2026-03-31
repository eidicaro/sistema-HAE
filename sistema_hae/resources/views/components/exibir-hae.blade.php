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

<!-- PENDENTES -->
<div class="hae-box">
    <div class="hae-header laranja">
        Pendentes
    </div>

    <div class="hae-list">
        @forelse($pendentes as $hae)
            <a href="/hae/{{ $hae->id }}" class="hae-item">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </a>
        @empty
            <p>Nenhuma HAE pendente</p>
        @endforelse
    </div>
</div>

<!-- DILIGÊNCIA -->
<div class="hae-box">
    <div class="hae-header amarelo">
        Com Diligência
    </div>

    <div class="hae-list">
        @forelse($diligencia as $hae)
            <div class="hae-item">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </div>
        @empty
            <p>Nenhuma HAE com diligência</p>
        @endforelse
    </div>
</div>

</div>
</body>
</html>