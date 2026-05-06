<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAE</title>
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

    <!-- infos Principais -->
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
        <p><strong>Carga Horária:</strong> {{ $hae->carga_horaria }}</p>
        @if( $hae->edital_aceito == 1)
            <p style="color: #009908"><strong style="color: #000">Edital:</strong>Aceito</p>
            @else
            <p style="color: #FF0000"><strong style="color: #000">Edital:</strong>Recusado</p>
        @endif


        <p>
            <strong>Status:</strong> 
            <span class="status status-{{ $hae->status }}">
            {{
                match($hae->status) {
                    'pendente' => 'Pendente',
                    'com_diligencia' => 'Com Diligência',
                    'em_execucao' => 'Em Execução',
                    'finalizada' => 'Finalizada',
                    'recusada' => 'Recusada',
                    default => $hae->status
                }
            }}
            </span>
        </p>

        {{-- 🔥 BOTÃO EDITAR (SÓ PROFESSOR E EM DILIGÊNCIA) --}}
        @if($user->role == 'professor' && $hae->status == \App\Models\Haes::STATUS_DILIGENCIA)
            <a href="{{ route('hae.edit', $hae->id) }}" class="btn-editar">
                Editar e reenviar
            </a>
        @endif

        @if($hae->status == 'em_execucao')
            <a href="/hae/{{ $hae->id }}/relatorio">
                Preencher Relatório
            </a>
        @endif
    </div>


        <!-- infos principais -->
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

        <p style="white-space: pre-line;">
            {{ $hae->cronograma }}
        </p>
    </div>

    <div class="bloco">
        <h2>Detalhes Específicos</h2>
        @includeIf('components.hae.' . $hae->tipo, ['hae' => $hae])
    </div>

    <!-- PARECERES -->
    <div class="bloco">
        <!-- mostra o parecer -->
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

        <!-- se o usuario for relator daquela hae, ele pode dar o parecer -->
        @php
            $usuario = auth()->user();

            $usuarioEhRelator = $hae->relatores->contains($usuario->id);
            $usuarioEhCoordenador = $usuario->role == 'coordenador';

            $podeDarParecer = $usuarioEhRelator || $usuarioEhCoordenador;

            $jaDeuParecer = $hae->pareceres->where('user_id', auth()->id())->count();
        @endphp

        @if($podeDarParecer && !$jaDeuParecer)
            <div class="bloco-parecer">
                <h3>Dar Parecer</h3>

                <form method="POST" action="/parecer/{{ $hae->id }}">
                    @csrf

                    <textarea name="comentario" required class="comentario"></textarea>

                    <button type="submit" class="btn-parecer">
                        Enviar parecer
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- DECISOES -->
    <div class="bloco">
        <!-- Mostra as Decisões -->
        <h2>Decisões</h2>

        @forelse($hae->decisoes as $decisao)
            <div class="item">
                <p><strong>Status:</strong> {{ $decisao->decisao }}</p>
                <p>{{ $decisao->comentario }}</p>
            </div>
        @empty
            <p class="vazio">Sem decisão ainda</p>
        @endforelse

        <!-- mensagem bala -->
        @if(session('error'))
            <div style="
                background: #ffdddd;
                color: #a00;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #a00;
                border-radius: 5px;
            ">
                {{ session('error') }}
            </div>
        @endif

        @if(session('sucesso'))
            <div style="
                background: #ddffdd;
                color: #0a0;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #0a0;
                border-radius: 5px;
            ">
                {{ session('sucesso') }}
            </div>
        @endif

        <!-- se for a direção, ele pode dar a decisão -->
        @if($user->role == 'direcao' && !in_array($hae->status, ['finalizada', 'recusada', 'em_execucao']))
            
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

<!-- Relatorio -->
    @if(isset($relatorio) && in_array($relatorio->status, ['enviado', 'reprovado', 'aprovado']))
        @include('relatorio.comparacao', ['relatorio' => $relatorio])
    @endif

    @if($user->role == 'direcao' 
        && $hae->status == 'em_execucao' 
        && isset($relatorio) 
        && $relatorio->status == 'enviado')
        <div style="display: flex;
                    justify-content: space-around;
                    margin: 2% 0 0 0;">
        
        <form method="POST" action="/relatorio/{{ $relatorio->id }}/aprovar">
            @csrf
            <button class="btn-rel-aprov">Aprovar Relatório</button>
        </form>

        <form method="POST" action="/relatorio/{{ $relatorio->id }}/reprovar">
            @csrf
            <button class="btn-rel-rec">Reprovar Relatório</button>
        </form>
        </div>
    @endif

    <!-- vizualizar relatorio -->
    @if(isset($relatorio))
    <div class="bloco">
        <h2>Relatório do Professor</h2>

        <p><strong>Título:</strong> {{ $relatorio->titulo }}</p>

        <div class="sub-bloco">
            <h3>Sumário Executivo</h3>
            <p>{{ $relatorio->sumario }}</p>
        </div>

        <div class="sub-bloco">
            <h3>Principais Resultados</h3>
            <p>{{ $relatorio->resultados_texto }}</p>
        </div>
    </div>
    @endif

    @if(isset($relatorio))
    <div class="bloco">
        <h2>Arquivos do Relatório</h2>

        @php
            $principal = $relatorio->arquivos->where('tipo', 'principal')->first();
            $comprovacoes = $relatorio->arquivos->where('tipo', 'comprovacao');
        @endphp

        {{-- 📄 Arquivo principal --}}
        @if($principal)
            <div class="item">
                <p><strong>Arquivo Principal:</strong></p>

                <a href="{{ route('arquivo.ver', $principal->id) }}" target="_blank">
                    Visualizar
                </a>

                <a href="{{ route('arquivo.download', $principal->id) }}">
                    Download
                </a>
            </div>
        @endif

        {{-- 📎 Comprovações --}}
        @if($comprovacoes->count())
            <div class="item">
                <p><strong>Comprovações:</strong></p>

                @foreach($comprovacoes as $arquivo)
                    <div>
                        <a href="{{ route('arquivo.ver', $arquivo->id) }}" target="_blank">
                            Visualizar
                        </a>

                        <a href="{{ route('arquivo.download', $arquivo->id) }}">
                            Download
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!$principal && !$comprovacoes->count())
            <p class="vazio">Nenhum arquivo enviado</p>
        @endif
    </div>
    @endif

</div>

</body>
</html>