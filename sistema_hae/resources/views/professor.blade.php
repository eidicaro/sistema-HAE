@extends('layouts.app')

@section('title', 'Minhas HAEs')
@section('eyebrow', 'Área do professor')
@section('page-title', 'Minhas atividades HAE')
@section('page-subtitle', 'Acompanhe suas submissões e as atividades em que você participa como relator.')

@section('header-actions')
    <a href="{{ route('hae.create') }}" class="button">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"/></svg>
        Nova HAE
    </a>
@endsection

@section('content')
    <section class="context-banner">
        <div class="context-banner__copy">
            <span class="context-banner__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8h16v8Z"/></svg></span>
            <div><h2>{{ $semestreAtual ? "Semestre {$semestreAtual->nome}" : 'Submissões indisponíveis' }}</h2><p>{{ $semestreAtual ? 'Todas as atividades exibidas pertencem ao período vigente.' : 'A direção ainda não ativou um semestre.' }}</p></div>
        </div>
        @if($semestreAtual)<a href="{{ route('hae.create') }}" class="button button--secondary">Enviar proposta</a>@endif
    </section>

    <section class="metrics-grid" aria-label="Resumo das minhas HAEs">
        <article class="metric-card metric-card--warning"><span class="metric-card__label">Em análise</span><strong class="metric-card__value">{{ $pendentes->count() }}</strong><span class="metric-card__detail">Aguardando decisão</span></article>
        <article class="metric-card metric-card--attention"><span class="metric-card__label">Precisam de ajuste</span><strong class="metric-card__value">{{ $diligencia->count() }}</strong><span class="metric-card__detail">HAEs em diligência</span></article>
        <article class="metric-card metric-card--info"><span class="metric-card__label">Em execução</span><strong class="metric-card__value">{{ $emExecucao->count() }}</strong><span class="metric-card__detail">Atividades em andamento</span></article>
        <article class="metric-card metric-card--success"><span class="metric-card__label">Concluídas</span><strong class="metric-card__value">{{ $finalizadas->count() }}</strong><span class="metric-card__detail">Relatórios aprovados</span></article>
    </section>

    <section class="section">
        <div class="section-heading"><div><h2>Minhas HAEs</h2><p>Selecione uma atividade para consultar detalhes e próximos passos.</p></div></div>
        @include('components.hae-board')
    </section>

    <section class="section">
        <div class="section-heading"><div><h2>Pareceres atribuídos</h2><p>Atividades que aguardam ou registram sua participação como relator.</p></div></div>
        <div class="review-list">
            @forelse($haesRelator as $hae)
                <a href="{{ route('hae.show', $hae->id) }}" class="review-card"><span class="review-card__mark"></span><span class="review-card__copy"><strong>{{ $hae->titulo }}</strong><small>{{ $hae->user->name }} · {{ $hae->created_at->format('d/m/Y') }}</small></span><span>→</span></a>
            @empty
                <div class="panel panel__body"><div class="empty-state"><span class="empty-state__icon">✓</span><p>Você não possui pareceres atribuídos neste semestre.</p></div></div>
            @endforelse
        </div>
    </section>
@endsection
