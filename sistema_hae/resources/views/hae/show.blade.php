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
    @php
        $user = auth()->user();
    @endphp

    <a href="{{ route($user->role) }}">Voltar</a>
<div class="hae-container">

    <h1 class="titulo">{{ $hae->titulo }}</h1>

    <div class="info">
            <p><strong>Tipo de HAE:</strong> 
            {{
                match($hae->tipo) {
                    'graduacao' => 'Projeto de Graduação',
                    'administracao' => 'Administração Acadêmica',
                    'estudos' => 'Estudos e Projetos',
                    'extensao' => 'Extensão',
                    'plantao' => 'Plantão Didático',
                    'ams' => 'Programa AMS',
                    default => ucfirst($hae->tipo)
                }
            }}
        </p>
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

        @includeIf('components.hae.' . $hae->tipo, ['hae' => $hae])
    </div>

    <div class="bloco">
        <h2>Pareceres</h2>

        @forelse($hae->pareceres as $parecer)
            <div class="item">
            <p>
                <strong>{{ $parecer->user->name }}</strong> 
                ({{ $parecer->tipo }})
            </p>

            <p>{{ $parecer->comentario }}</p>
            </div>
        @empty
            <p class="vazio">Sem pareceres ainda</p>
        @endforelse

        @php
            $usuarioEhRelator = $hae->relatores->contains(auth()->id());
            $jaDeuParecer = $hae->pareceres->where('user_id', auth()->id())->count();
        @endphp

        @if($usuarioEhRelator && !$jaDeuParecer)
            <div class="bloco-parecer">
                <h2>Dar Parecer</h2>

                <form method="POST" action="/parecer/{{ $hae->id }}">
                    @csrf

                    <textarea name="comentario" required class="comentario"></textarea>

                    <button type="submit" class="btn-parecer">Enviar parecer</button>
                </form>
            </div>
        @endif
    </div>

<!-- vê se quem é o usuario -->
    @php
    $user = auth()->user();
    @endphp

    <div class="bloco">
    <h2>Decisão</h2>

    @forelse($hae->decisoes as $decisao)
        <div class="item">
            <p><strong>Decisão:</strong> {{ $decisao->decisao}}</p>
            <p>{{ $decisao->comentario }}</p>
        </div>
    @empty
        <p class="vazio">Sem decisão ainda</p>
    @endforelse

    {{-- 🔥 BOTÕES SÓ PARA DIREÇÃO --}}
    @if($user->role == 'direcao' && $hae->status != 'finalizada' && $hae->status != 'recusada')
        
        <div class="bloco-decisao">
            <h3>Tomar decisão</h3>

            <form method="POST" action="/direcao/decisao/{{ $hae->id }}">
                @csrf

                <textarea name="comentario" placeholder="Comentário (opcional)" class="comentario"></textarea>

                    <div class="acoes">
                        <button name="acao" value="aprovada" class="btn-aprovar">
                            Aprovar
                        </button>

                        <button name="acao" value="recusada" class="btn-recusar">
                            Recusar
                        </button>

                        <button name="acao" value="diligencia" class="btn-diligencia">
                            Pedir Diligência
                        </button>
                    </div>
                </form>
            </div>

        @endif
    </div>

</div>

</body>
</html>