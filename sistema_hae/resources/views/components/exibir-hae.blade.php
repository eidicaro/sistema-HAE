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

    @php
    $user = auth()->user();
    @endphp

    <div class="minhas-haes">

        <!-- PENDENTES -->
        <div class="hae-box">
            <div class="hae-header laranja">
                Pendentes
                @if(auth()->user()->role == 'direcao')
                <span class="qtd">({{ $pendentes->count() }})</span>
                @endif
            </div>

            <div class="hae-list">
                @forelse($pendentes as $hae)
                <a href="/hae/{{ $hae->id }}" class="hae-item laranja">

                    <div class="hae-info">

                        <span class="titulo">
                            {{ $hae->titulo }}
                        </span>

                        <span class="professor">
                            Professor:
                            {{ $hae->user->name }}
                        </span>

                        <span class="data">
                            Submetida em
                            {{ $hae->created_at->format('d/m/Y') }}
                        </span>

                    </div>

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
                @if(auth()->user()->role == 'direcao')
                <span class="qtd">({{ $diligencia->count() }})</span>
                @endif
            </div>

            <div class="hae-list">
                @forelse($diligencia as $hae)
                <a href="/hae/{{ $hae->id }}" class="hae-item amarelo">

                    <div class="hae-info">

                        <span class="titulo">
                            {{ $hae->titulo }}
                        </span>

                        <span class="professor">
                            Professor:
                            {{ $hae->user->name }}
                        </span>

                        <span class="data">
                            Submetida em
                            {{ $hae->created_at->format('d/m/Y') }}
                        </span>

                    </div>

                </a>
                @empty
                <p>Nenhuma HAE com diligência</p>
                @endforelse
            </div>
        </div>

        <!-- EM EXECUÇÃO -->
        <div class="hae-box">
            <div class="hae-header azul">
                Em Execução
                @if(auth()->user()->role == 'direcao')
                <span class="qtd">({{ $emExecucao->count() }})</span>
                @endif
            </div>

            <div class="hae-list">
                @forelse($emExecucao as $hae)
                <a href="/hae/{{ $hae->id }}" class="hae-item azul">

                    <div class="hae-info">

                        <span class="titulo">
                            {{ $hae->titulo }}
                        </span>

                        <span class="professor">
                            Professor:
                            {{ $hae->user->name }}
                        </span>

                        <span class="data">
                            Submetida em
                            {{ $hae->created_at->format('d/m/Y') }}
                        </span>

                    </div>

                </a>
                @empty
                <p>Nenhuma HAE em execução</p>
                @endforelse
            </div>
        </div>

    </div>
</body>

</html>