@php
    $gruposHae = $grupos ?? [
        ['status' => 'pendente', 'titulo' => 'Pendentes', 'descricao' => 'Aguardando análise', 'tom' => 'warning', 'itens' => $pendentes ?? collect()],
        ['status' => 'com_diligencia', 'titulo' => 'Em diligência', 'descricao' => 'Ajustes solicitados', 'tom' => 'attention', 'itens' => $diligencia ?? collect()],
        ['status' => 'em_execucao', 'titulo' => 'Em execução', 'descricao' => 'Atividades aprovadas', 'tom' => 'info', 'itens' => $emExecucao ?? collect()],
        ['status' => 'finalizada', 'titulo' => 'Finalizadas', 'descricao' => 'Relatório aprovado', 'tom' => 'success', 'itens' => $finalizadas ?? collect()],
        ['status' => 'recusada', 'titulo' => 'Recusadas', 'descricao' => 'Submissões encerradas', 'tom' => 'danger', 'itens' => $recusadas ?? collect()],
    ];
@endphp

<div class="hae-board">
    @foreach($gruposHae as $grupo)
        <section class="status-column status-column--{{ $grupo['tom'] }}" data-status-column>
            <header class="status-column__header">
                <span class="status-column__marker"></span>
                <div>
                    <h3>{{ $grupo['titulo'] }}</h3>
                    <p>{{ $grupo['descricao'] }}</p>
                </div>
                <span class="count-badge">{{ $grupo['itens']->count() }}</span>
            </header>

            <div class="status-column__list">
                @forelse($grupo['itens'] as $hae)
                    <a href="{{ route('hae.show', $hae->id) }}" class="hae-card" data-hae-item>
                        <span class="hae-card__title titulo">{{ $hae->titulo }}</span>
                        <span class="hae-card__owner professor">{{ $hae->user->name }}</span>
                        <span class="hae-card__meta">
                            <span>{{ $hae->curso }}</span>
                            <time datetime="{{ $hae->created_at->toDateString() }}">{{ $hae->created_at->format('d/m/Y') }}</time>
                        </span>
                        <span class="hae-card__link">Abrir HAE <span aria-hidden="true">→</span></span>
                    </a>
                @empty
                    <div class="empty-state" data-empty-state>
                        <span class="empty-state__icon">✓</span>
                        <p>Nenhuma HAE nesta etapa.</p>
                    </div>
                @endforelse
            </div>
        </section>
    @endforeach
</div>
