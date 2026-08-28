@extends('layouts.app')

@section('title', 'Painel da Coordenação')
@section('eyebrow', 'Coordenação de curso')
@section('page-title', 'Acompanhamento do curso')
@section('page-subtitle', 'Consulte as propostas do curso e registre seus pareceres com rapidez.')

@section('content')
    <section class="context-banner">
        <div class="context-banner__copy">
            <span class="context-banner__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 1 9l4 2.18V17l7 4 7-4v-5.82L21 10v7h2V9L12 3Zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9ZM17 15.82l-5 2.86-5-2.86v-3.55L12 15l5-2.73v3.55Z"/></svg></span>
            <div><h2>{{ auth()->user()->curso ?: 'Curso não informado' }}</h2><p>{{ $semestreAtual ? "Semestre {$semestreAtual->nome}" : 'Nenhum semestre ativo no momento' }}</p></div>
        </div>
    </section>

    <section class="metrics-grid">
        <article class="metric-card metric-card--warning"><span class="metric-card__label">Pendentes</span><strong class="metric-card__value">{{ $pendentes->count() }}</strong><span class="metric-card__detail">Novas submissões do curso</span></article>
        <article class="metric-card metric-card--attention"><span class="metric-card__label">Em diligência</span><strong class="metric-card__value">{{ $diligencia->count() }}</strong><span class="metric-card__detail">Em ajuste pelo professor</span></article>
        <article class="metric-card metric-card--info"><span class="metric-card__label">Em execução</span><strong class="metric-card__value">{{ $emExecucao->count() }}</strong><span class="metric-card__detail">Atividades aprovadas</span></article>
        <article class="metric-card metric-card--success"><span class="metric-card__label">Para meu parecer</span><strong class="metric-card__value">{{ $haesRelator->count() }}</strong><span class="metric-card__detail">Atribuições como relator</span></article>
    </section>

    <section class="section">
        <div class="section-heading"><div><h2>HAEs do curso</h2><p>Acompanhe a situação das atividades vinculadas à coordenação.</p></div></div>
        @include('components.hae-board')
    </section>

    <section class="section">
        <div class="section-heading"><div><h2>Pareceres atribuídos</h2><p>Abra a HAE para consultar a proposta e enviar seu parecer.</p></div></div>
        <div class="review-list">
            @forelse($haesRelator as $hae)
                <a href="{{ route('hae.show', $hae->id) }}" class="review-card"><span class="review-card__mark"></span><span class="review-card__copy"><strong>{{ $hae->titulo }}</strong><small>{{ $hae->user->name }} · {{ $hae->created_at->format('d/m/Y') }}</small></span><span>→</span></a>
            @empty
                <div class="panel panel__body"><div class="empty-state"><span class="empty-state__icon">✓</span><p>Nenhum parecer atribuído neste semestre.</p></div></div>
            @endforelse
        </div>
    </section>
@endsection
