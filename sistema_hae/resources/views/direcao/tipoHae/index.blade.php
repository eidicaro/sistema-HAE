@extends('layouts.app')

@section('title', 'Tipos de HAE')
@section('eyebrow', 'Configuração institucional')
@section('page-title', 'Tipos e limites de HAE')
@section('page-subtitle', 'Configure as categorias disponíveis e a capacidade de horas de cada uma.')

@section('header-actions')
    <a href="{{ route('direcao.tipos-hae.create') }}" class="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"/></svg>Novo tipo</a>
@endsection

@section('content')
    <section class="panel">
        <div class="panel__header"><div><h2>Tipos cadastrados</h2></div><span class="count-badge">{{ $tipos->count() }}</span></div>
        <div class="table-wrap"><table class="data-table">
            <thead><tr><th>Tipo</th><th>Descrição</th><th>Subtipos</th><th>Limite compartilhado</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody>
                @forelse($tipos as $tipo)
                    <tr>
                        <td><strong>{{ $tipo->nome }}</strong></td>
                        <td>{{ $tipo->descricao ?: 'Sem descrição' }}</td>
                        <td>{{ $tipo->subtipos_count }}</td>
                        <td><strong>{{ $tipo->limite }}h</strong> por semestre</td>
                        <td><span class="tag {{ $tipo->ativo ? 'tag--active' : 'tag--inactive' }}">{{ $tipo->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                        <td><div class="data-table__actions">
                            <a href="{{ route('direcao.tipos-hae.edit', $tipo) }}" class="button button--secondary button--small">Editar</a>
                            <form action="{{ route('direcao.tipos-hae.toggle', $tipo) }}" method="POST">@csrf<button type="submit" class="button button--ghost button--small">{{ $tipo->ativo ? 'Desativar' : 'Ativar' }}</button></form>
                            <form action="{{ route('direcao.tipos-hae.destroy', $tipo) }}" method="POST" data-confirm="Deseja realmente excluir este tipo?">@csrf @method('DELETE')<button type="submit" class="button button--ghost button--small">Excluir</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><p>Nenhum tipo de HAE cadastrado.</p></div></td></tr>
                @endforelse
            </tbody>
        </table></div>
    </section>
@endsection
