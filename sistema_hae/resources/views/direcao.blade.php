@extends('layouts.app')

@section('title', 'Painel da Direção')
@section('eyebrow', 'Direção acadêmica')
@section('page-title', 'Visão geral das HAEs')
@section('page-subtitle', 'Acompanhe submissões, decisões, execução e capacidade institucional em um só lugar.')

@section('header-actions')
    <a href="{{ route('direcao.exportarcsv') }}" class="button button--secondary">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 9h-4V3H9v6H5l7 7 7-7ZM5 18v2h14v-2H5Z"/></svg>
        Exportar planilha
    </a>
@endsection

@section('content')
    <section class="context-banner" aria-label="Semestre em acompanhamento">
        <div class="context-banner__copy">
            <span class="context-banner__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8h16v8ZM4 9V7a1 1 0 0 1 1-1h1v2h2V6h8v2h2V6h1a1 1 0 0 1 1 1v2H4Z"/></svg></span>
            <div>
                <h2>{{ $semestreAtual ? "Semestre {$semestreAtual->nome}" : 'Nenhum semestre ativo' }}</h2>
                <p>{{ $semestreAtual ? 'Os indicadores abaixo consideram exclusivamente este período.' : 'Ative um semestre para receber novas submissões.' }}</p>
            </div>
        </div>
        <a href="{{ route('semestres.index') }}" class="button button--secondary">Gerenciar semestre</a>
    </section>

    <section class="metrics-grid" aria-label="Resumo operacional">
        <article class="metric-card metric-card--warning"><span class="metric-card__label">Aguardando decisão</span><strong class="metric-card__value">{{ $pendentes->count() }}</strong><span class="metric-card__detail">HAEs pendentes de análise</span></article>
        <article class="metric-card metric-card--attention"><span class="metric-card__label">Em diligência</span><strong class="metric-card__value">{{ $diligencia->count() }}</strong><span class="metric-card__detail">Aguardando ajuste do professor</span></article>
        <article class="metric-card metric-card--info"><span class="metric-card__label">Em execução</span><strong class="metric-card__value">{{ $emExecucao->count() }}</strong><span class="metric-card__detail">Atividades em andamento</span></article>
        <article class="metric-card metric-card--success"><span class="metric-card__label">Finalizadas</span><strong class="metric-card__value">{{ $finalizadas->count() }}</strong><span class="metric-card__detail">Relatórios já aprovados</span></article>
    </section>

    <section class="section" aria-labelledby="gestao-title">
        <div class="section-heading"><div><h2 id="gestao-title">Acesso rápido</h2><p>Cadastros e tarefas administrativas mais utilizadas.</p></div></div>
        <div class="management-grid">
            <a href="{{ route('direcao.relatores') }}" class="management-card">
                <span class="management-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg></span>
                <span><h3>Definir relatores</h3><p>Distribua as HAEs entre professores e coordenadores.</p></span><span class="management-card__arrow">→</span>
            </a>
            <a href="{{ route('direcao.professores.index') }}" class="management-card">
                <span class="management-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z"/></svg></span>
                <span><h3>Professores</h3><p>Cadastre, localize e atualize contas de professores.</p></span><span class="management-card__arrow">→</span>
            </a>
            <a href="{{ route('direcao.tipos-hae.index') }}" class="management-card">
                <span class="management-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.63 5.84.95-.95a1 1 0 0 0-1.41-1.41l-.95.95A8 8 0 1 0 19.57 7.8l.95-.95a1 1 0 1 0-1.41-1.41l-.95.95a8.03 8.03 0 0 0-.53-.55ZM12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/></svg></span>
                <span><h3>Tipos e limites</h3><p>Organize categorias e defina a capacidade de horas.</p></span><span class="management-card__arrow">→</span>
            </a>
            <a href="{{ route('semestres.index') }}" class="management-card">
                <span class="management-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8h16v8ZM4 9V7a1 1 0 0 1 1-1h1v2h2V6h8v2h2V6h1a1 1 0 0 1 1 1v2H4Z"/></svg></span>
                <span><h3>Semestres</h3><p>Cadastre períodos e selecione o semestre vigente.</p></span><span class="management-card__arrow">→</span>
            </a>
            <a href="{{ route('direcao.resultados') }}" class="management-card">
                <span class="management-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16v2H4v-2Zm1-2h3V9H5v8Zm5 0h3V3h-3v14Zm5 0h3v-6h-3v6Z"/></svg></span>
                <span><h3>Resultados</h3><p>Consulte atividades finalizadas e submissões recusadas.</p></span><span class="management-card__arrow">→</span>
            </a>
        </div>
    </section>

    <section class="section" id="haes" aria-labelledby="haes-title">
        <div class="section-heading">
            <div><h2 id="haes-title">Acompanhamento das HAEs</h2><p>Abra uma atividade para consultar pareceres, registrar decisões ou avaliar o relatório.</p></div>
            <div class="search-bar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 3a6.5 6.5 0 1 0 3.98 11.64L19.85 21 21 19.85l-6.36-6.37A6.5 6.5 0 0 0 9.5 3Zm0 2a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z"/></svg><label for="pesquisaHae" class="sr-only">Pesquisar por título, professor ou curso</label><input type="search" id="pesquisaHae" placeholder="Pesquisar título, professor ou curso..." autocomplete="off"></div>
        </div>
        @include('components.hae-board')
    </section>

    <section class="section" aria-labelledby="capacidade-title">
        <div class="section-heading">
            <div><h2 id="capacidade-title">Capacidade de carga horária</h2><p>Horas aprovadas ou finalizadas por tipo no semestre atual.</p></div>
            <a href="{{ route('direcao.tipos-hae.index') }}" class="button button--secondary button--small">Ajustar limites</a>
        </div>
        <div class="panel"><div class="table-wrap"><table class="data-table">
            <thead><tr><th>Tipo de HAE</th><th>Utilização</th><th>Disponível</th></tr></thead>
            <tbody>
                @forelse($dadosLimites as $dado)
                    @php
                        $percentual = $dado['limite'] > 0 ? min(100, round(($dado['usado'] / $dado['limite']) * 100)) : 0;
                        $classeBarra = $percentual >= 90 ? 'progress__bar--danger' : ($percentual >= 70 ? 'progress__bar--warning' : 'progress__bar--safe');
                    @endphp
                    <tr>
                        <td><strong>{{ $dado['tipo'] }}</strong></td>
                        <td class="capacity-cell"><div class="capacity-numbers"><span>{{ $dado['usado'] }}h utilizadas</span><span>{{ $dado['limite'] }}h totais</span></div><div class="progress" aria-label="{{ $percentual }}% utilizado"><span class="progress__bar {{ $classeBarra }}" style="width: {{ $percentual }}%"></span></div></td>
                        <td><span class="tag {{ $dado['restante'] <= 0 ? 'tag--danger' : 'tag--active' }}">{{ $dado['restante'] }}h restantes</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3"><div class="empty-state"><p>Nenhum tipo ativo para exibir.</p></div></td></tr>
                @endforelse
            </tbody>
        </table></div></div>
    </section>
@endsection
