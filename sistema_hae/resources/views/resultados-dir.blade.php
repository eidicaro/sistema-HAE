@extends('layouts.app')

@section('title', 'Resultados')
@section('eyebrow', 'Direção acadêmica')
@section('page-title', 'Resultados do semestre')
@section('page-subtitle', 'Consulte as atividades encerradas e o resultado das submissões.')

@section('content')
    <section class="metrics-grid metrics-grid--compact">
        <article class="metric-card metric-card--success"><span class="metric-card__label">Finalizadas</span><strong class="metric-card__value">{{ $finalizadas->count() }}</strong><span class="metric-card__detail">Atividades com relatório aprovado</span></article>
        <article class="metric-card"><span class="metric-card__label">Recusadas</span><strong class="metric-card__value">{{ $recusadas->count() }}</strong><span class="metric-card__detail">Propostas não aprovadas</span></article>
    </section>

    <section class="section">
        <div class="section-heading"><div><h2>Atividades encerradas</h2><p>Selecione uma HAE para consultar seu histórico completo.</p></div></div>
        @include('components.hae-board', ['grupos' => [
            ['status' => 'finalizada', 'titulo' => 'Finalizadas', 'descricao' => 'Relatório aprovado', 'tom' => 'success', 'itens' => $finalizadas],
            ['status' => 'recusada', 'titulo' => 'Recusadas', 'descricao' => 'Submissões encerradas', 'tom' => 'danger', 'itens' => $recusadas],
        ]])
    </section>
@endsection
