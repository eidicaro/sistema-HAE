<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('../../css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('../../css/fonte.css') }}">
</head>
<body>
    <a href="/professor">voltar</a>
<div class="hae-container">

    <h1 class="titulo">{{ $hae->titulo }}</h1>

    <div class="info">
        <p><strong>Professor:</strong> {{ $hae->user->name }}</p>
        <p><strong>Curso:</strong> {{ $hae->curso }}</p>
        <p><strong>Carga Horaria:</strong> {{ $hae->carga_horaria }}</p>
        <p><strong>Status:</strong> <span class="status">{{ $hae->status }}</span></p>
    </div>

    <div class="bloco">
        <h2>Resumo</h2>
        <p>{{ $hae->resumo }}</p>
    </div>

    <div class="bloco">
        <h2>Justificativa</h2>
        <p>{{ $hae->justificativa }}</p>
    </div>

    <div class="bloco">
        <h2>Cronograma</h2>
        <ul class="cronograma">
            <li><strong>Fevereiro:</strong> {{ $hae->fevereiro }}</li>
            <li><strong>Março:</strong> {{ $hae->marco }}</li>
            <li><strong>Abril:</strong> {{ $hae->abril }}</li>
            <li><strong>Maio:</strong> {{ $hae->maio }}</li>
            <li><strong>Junho:</strong> {{ $hae->junho }}</li>
        </ul>
    </div>

    <div class="bloco">
        <h2>Detalhes Específicos</h2>

        @if($hae->tipo == 'graduacao' && $hae->graduacao)
            <p><strong>Tipo:</strong> {{ $hae->graduacao->tipo_graduacao }}</p>
            <p><strong>Orientações:</strong> {{ $hae->graduacao->orientacoes }}</p>
            <p><strong>Bancas (Orientador):</strong> {{ $hae->graduacao->bancas_orientador }}</p>
            <p><strong>Bancas (Membro):</strong> {{ $hae->graduacao->bancas_membro }}</p>
        @endif
    </div>

    <div class="bloco">
        <h2>Pareceres</h2>

        @forelse($hae->pareceres as $parecer)
            <div class="item">
                <p><strong>Relator:</strong> {{ $parecer->relator_id }}</p>
                <p>{{ $parecer->comentario }}</p>
            </div>
        @empty
            <p class="vazio">Sem pareceres ainda</p>
        @endforelse
    </div>

    <div class="bloco">
        <h2>Decisão</h2>

        @forelse($hae->decisoes as $decisao)
            <div class="item">
                <p><strong>Decisão:</strong> {{ $decisao->decisao }}</p>
                <p>{{ $decisao->comentario }}</p>
            </div>
        @empty
            <p class="vazio">Sem decisão ainda</p>
        @endforelse
    </div>

</div>

</body>
</html>