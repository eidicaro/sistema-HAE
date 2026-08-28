@extends('layouts.app')

@php
    $user = auth()->user();
    $statusLabel = match($hae->status) {
        'pendente' => 'Pendente',
        'com_diligencia' => 'Em diligência',
        'em_execucao' => 'Em execução',
        'finalizada' => 'Finalizada',
        'recusada' => 'Recusada',
        default => $hae->status,
    };
    $usuarioEhRelator = $hae->relatores->contains($user->id);
    $usuarioEhCoordenador = $user->role === 'coordenador';
    $podeDarParecer = $usuarioEhRelator || $usuarioEhCoordenador;
    $jaDeuParecer = $hae->pareceres->where('user_id', $user->id)->isNotEmpty();
@endphp

@section('title', $hae->titulo)
@section('eyebrow', 'Detalhes da atividade')
@section('page-title', 'Consulta da HAE')
@section('page-subtitle', 'Revise a proposta, os pareceres e o histórico de decisões.')

@section('header-actions')
    <a href="{{ route('hae.pdf', $hae->id) }}" class="button">Baixar PDF</a>
    <a href="{{ route($user->role) }}" class="button button--secondary">← Voltar ao painel</a>
@endsection

@section('content')
    <div class="detail-layout">
        <div class="detail-main">
            <section class="detail-hero">
                <div class="detail-hero__top">
                    <div><h2>{{ $hae->titulo }}</h2><p class="detail-hero__type">{{ $hae->tipoHae->nome ?? 'Tipo não definido' }}@if($hae->subtipoHae) · {{ $hae->subtipoHae->nome }}@endif</p></div>
                    <span class="status-pill status-pill--{{ $hae->status }}">{{ $statusLabel }}</span>
                </div>
                <dl class="detail-meta">
                    <div><dt>Professor</dt><dd>{{ $hae->user->name }}</dd></div>
                    <div><dt>Curso</dt><dd>{{ $hae->curso }}</dd></div>
                    <div><dt>Carga horária</dt><dd>{{ $hae->carga_horaria }}h semanais</dd></div>
                    <div><dt>Semestre</dt><dd>{{ $hae->semestre->nome ?? 'Não informado' }}</dd></div>
                    <div><dt>Submetida em</dt><dd>{{ $hae->created_at->format('d/m/Y') }}</dd></div>
                    <div><dt>Edital</dt><dd>{{ $hae->edital_aceito ? 'Aceito' : 'Não aceito' }}</dd></div>
                </dl>
            </section>

            <section class="content-block"><h3>Resumo</h3><p>{{ $hae->resumo }}</p></section>
            <section class="content-block"><h3>Justificativa</h3><p>{{ $hae->justificativa }}</p></section>
            <section class="content-block"><h3>Resultados esperados</h3><p>{{ $hae->resultados_esperados ?: 'Não informado.' }}</p></section>
            <section class="content-block"><h3>Indicadores</h3><p>{{ $hae->indicadores ?: 'Não informado.' }}</p></section>

            <section class="content-block">
                <h3>Cronograma</h3>
                <div class="table-wrap"><table class="data-table">
                    <thead><tr><th>Mês</th><th>Desenvolvimento previsto</th></tr></thead>
                    <tbody>
                        @for($i = 1; $i <= 5; $i++)
                            <tr><td><strong>Mês {{ $i }}</strong></td><td>{{ $hae->{'mes_'.$i} ?: 'Não informado.' }}</td></tr>
                        @endfor
                    </tbody>
                </table></div>
            </section>
            <section class="content-block"><h3>Horários da HAE</h3><p>{{ $hae->horarios_hae ?: 'Não informado.' }}</p></section>

            <section class="content-block">
                <h3>Pareceres</h3>
                <div class="timeline">
                    @forelse($hae->pareceres as $parecer)
                        <article class="timeline-item"><div class="timeline-item__head"><strong>{{ $parecer->user->name }}</strong><span>{{ $parecer->tipo === 'coordenador' ? 'Coordenação' : 'Relator' }} · {{ $parecer->created_at->format('d/m/Y') }}</span></div><p>{{ $parecer->comentario }}</p></article>
                    @empty
                        <div class="empty-state"><p>Nenhum parecer registrado.</p></div>
                    @endforelse
                </div>

                @if($podeDarParecer && !$jaDeuParecer)
                    <form method="POST" action="{{ route('parecer.store', $hae->id) }}" class="decision-form" style="margin-top: 18px">
                        @csrf
                        <div class="field"><label for="comentario-parecer">Registrar parecer</label><textarea id="comentario-parecer" name="comentario" placeholder="Apresente sua análise da proposta..." required></textarea></div>
                        <div class="actions-row"><button type="submit" class="button">Enviar parecer</button></div>
                    </form>
                @endif
            </section>

            <section class="content-block">
                <h3>Histórico de decisões</h3>
                <div class="timeline">
                    @forelse($hae->decisoes as $decisao)
                        <article class="timeline-item"><div class="timeline-item__head"><strong>{{ ucfirst($decisao->decisao) }}</strong><span>{{ $decisao->created_at->format('d/m/Y H:i') }}</span></div><p>{{ $decisao->comentario ?: 'Sem comentário.' }}</p></article>
                    @empty
                        <div class="empty-state"><p>Nenhuma decisão registrada.</p></div>
                    @endforelse
                </div>
            </section>

            @if($relatorio)
                <section class="content-block">
                    <div class="section-heading" style="margin-bottom: 14px"><div><h3>Relatório do professor</h3><p>Situação: {{ ucfirst($relatorio->status) }}</p></div></div>
                    <p><strong>{{ $relatorio->titulo }}</strong></p>
                    <div class="timeline" style="margin-top: 16px">
                        <article class="timeline-item"><div class="timeline-item__head"><strong>Sumário executivo</strong></div><p>{{ $relatorio->sumario }}</p></article>
                        <article class="timeline-item"><div class="timeline-item__head"><strong>Principais resultados</strong></div><p>{{ $relatorio->resultados_texto }}</p></article>
                    </div>
                </section>

                @include('relatorio.comparacao', ['relatorio' => $relatorio])

                <section class="content-block">
                    <h3>Arquivos do relatório</h3>
                    @php
                        $principal = $relatorio->arquivos->where('tipo', 'principal')->first();
                        $comprovacoes = $relatorio->arquivos->where('tipo', 'comprovacao');
                    @endphp
                    <div class="file-list">
                        @if($principal)
                            <div class="file-item"><span class="file-item__name"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Zm0 2.5L17.5 8H14V4.5ZM6 20V4h6v6h6v10H6Z"/></svg>Arquivo principal</span><span class="file-item__actions"><a href="{{ route('arquivo.ver', $principal->id) }}" target="_blank" class="text-link">Visualizar</a><a href="{{ route('arquivo.download', $principal->id) }}" class="text-link">Baixar</a></span></div>
                        @endif
                        @foreach($comprovacoes as $index => $arquivo)
                            <div class="file-item"><span class="file-item__name"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.5 6.5v11a4.5 4.5 0 0 1-9 0V5a3 3 0 0 1 6 0v11.5a1.5 1.5 0 0 1-3 0V6H12v10.5h.01L12 5a1.5 1.5 0 0 0-3 0v12.5a3 3 0 0 0 6 0v-11h1.5Z"/></svg>Comprovação {{ $index + 1 }}</span><span class="file-item__actions"><a href="{{ route('arquivo.ver', $arquivo->id) }}" target="_blank" class="text-link">Visualizar</a><a href="{{ route('arquivo.download', $arquivo->id) }}" class="text-link">Baixar</a></span></div>
                        @endforeach
                        @if(!$principal && $comprovacoes->isEmpty())<div class="empty-state"><p>Nenhum arquivo enviado.</p></div>@endif
                    </div>
                </section>
            @endif
        </div>

        <aside class="detail-aside sticky-panel">
            <section class="panel panel--accent">
                <div class="panel__header"><h3>Próximas ações</h3></div>
                <div class="panel__body">
                    <div class="decision-actions">
                        @if($user->role === 'professor' && $hae->status === \App\Models\Haes::STATUS_DILIGENCIA)
                            <a href="{{ route('hae.edit', $hae->id) }}" class="button">Editar e reenviar</a>
                        @endif
                        @if($user->role === 'professor' && $hae->user_id === $user->id && $hae->status === \App\Models\Haes::STATUS_EM_EXECUCAO && (!$relatorio || $relatorio->status === \App\Models\Relatorio::STATUS_RECUSADO))
                            <a href="{{ route('relatorio.create', $hae->id) }}" class="button">Preencher relatório</a>
                        @endif
                        @if($user->role === 'professor' && $relatorio?->status === \App\Models\Relatorio::STATUS_ENVIADO)
                            <p style="margin: 0; color: var(--ink-600)">Relatório enviado. Aguarde a avaliação da direção.</p>
                        @endif
                        @if(!($user->role === 'professor' && in_array($hae->status, [\App\Models\Haes::STATUS_DILIGENCIA, \App\Models\Haes::STATUS_EM_EXECUCAO])))
                            <p style="margin: 0; color: var(--ink-600)">Consulte abaixo as informações e ações disponíveis para esta etapa.</p>
                        @endif
                    </div>
                </div>
            </section>

            @if($user->role === 'direcao' && in_array($hae->status, ['pendente', 'com_diligencia']))
                <section class="panel">
                    <div class="panel__header"><h3>Decisão da direção</h3></div>
                    <div class="panel__body">
                        <form method="POST" action="{{ route('direcao.decisao', $hae->id) }}" class="decision-form">
                            @csrf
                            <div class="field"><label for="comentario-decisao">Comentário</label><textarea id="comentario-decisao" name="comentario" placeholder="Justificativa ou orientação para o professor..."></textarea></div>
                            <div class="decision-actions"><button name="acao" value="aprovada" class="button button--success">Aprovar proposta</button><button name="acao" value="diligencia" class="button button--warning">Solicitar diligência</button><button name="acao" value="recusada" class="button button--secondary">Recusar proposta</button></div>
                        </form>
                    </div>
                </section>
            @endif

            @if($user->role === 'direcao' && $hae->status === 'em_execucao' && $relatorio?->status === 'enviado')
                <section class="panel">
                    <div class="panel__header"><h3>Avaliar relatório</h3></div>
                    <div class="panel__body"><div class="decision-actions">
                        <form method="POST" action="{{ route('relatorio.aprovar', $relatorio->id) }}">@csrf<button class="button button--success" style="width: 100%">Aprovar relatório</button></form>
                        <form method="POST" action="{{ route('relatorio.reprovar', $relatorio->id) }}">@csrf<button class="button button--secondary" style="width: 100%">Solicitar correção</button></form>
                    </div></div>
                </section>
            @endif

            <section class="panel">
                <div class="panel__header"><h3>Relatores</h3><span class="count-badge">{{ $hae->relatores->count() }}</span></div>
                <div class="panel__body"><div class="reviewer-card__tags">@forelse($hae->relatores as $relator)<span class="tag">{{ $relator->name }}</span>@empty<span style="color: var(--ink-500)">Nenhum relator atribuído.</span>@endforelse</div></div>
            </section>
        </aside>
    </div>
@endsection
