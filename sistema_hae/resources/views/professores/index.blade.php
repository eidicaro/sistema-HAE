@extends('layouts.app')

@section('title', 'Professores')
@section('eyebrow', 'Gestão de usuários')
@section('page-title', 'Professores')
@section('page-subtitle', 'Cadastre e mantenha atualizados os acessos dos professores.')

@section('header-actions')
    <a href="{{ route('direcao.professores.create') }}" class="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"/></svg>Novo professor</a>
@endsection

@section('content')
    <section class="panel">
        <div class="panel__header">
            <div><h2>Professores cadastrados</h2></div>
            <form method="GET" action="{{ route('direcao.professores.index') }}" class="search-form">
                <label for="busca" class="sr-only">Pesquisar professor</label>
                <input type="search" id="busca" name="busca" value="{{ $busca }}" placeholder="Nome ou e-mail">
                <button type="submit" class="button button--secondary">Pesquisar</button>
            </form>
        </div>
        <div class="table-wrap"><table class="data-table">
            <thead><tr><th>Professor</th><th>E-mail</th><th>Ações</th></tr></thead>
            <tbody>
                @forelse($professores as $professor)
                    <tr>
                        <td><div class="user-summary"><span class="user-summary__avatar">{{ mb_strtoupper(mb_substr($professor->name, 0, 1)) }}</span><span class="user-summary__text"><strong style="color: var(--ink-900)">{{ $professor->name }}</strong><small>Professor</small></span></div></td>
                        <td>{{ $professor->email }}</td>
                        <td><div class="data-table__actions">
                            <a href="{{ route('direcao.professores.edit', $professor) }}" class="button button--secondary button--small">Editar</a>
                            <form action="{{ route('direcao.professores.destroy', $professor) }}" method="POST" data-confirm="Deseja realmente excluir este professor?">@csrf @method('DELETE')<button type="submit" class="button button--ghost button--small">Excluir</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="3"><div class="empty-state"><p>Nenhum professor encontrado.</p></div></td></tr>
                @endforelse
            </tbody>
        </table></div>
    </section>
@endsection
