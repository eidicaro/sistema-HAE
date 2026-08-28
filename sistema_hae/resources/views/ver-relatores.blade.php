@extends('layouts.app')

@section('title', 'Relatores')
@section('eyebrow', 'Distribuição de análise')
@section('page-title', 'Definir relatores')
@section('page-subtitle', 'Associe professores e coordenadores às HAEs do semestre ativo.')

@section('content')
    <details class="panel directory-panel">
        <summary class="directory-toggle"><span>Usuários disponíveis <span class="count-badge" style="display: inline-grid; margin-left: 8px">{{ $usuarios->count() }}</span></span><span>Consultar diretório</span></summary>
        <div class="directory-content">
            <div class="search-bar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9.5 3a6.5 6.5 0 1 0 3.98 11.64L19.85 21 21 19.85l-6.36-6.37A6.5 6.5 0 0 0 9.5 3Zm0 2a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z"/></svg><label for="buscarUsuario" class="sr-only">Buscar usuário</label><input type="search" id="buscarUsuario" placeholder="Buscar por nome ou perfil..."></div>
            <div class="directory-users" id="usuariosDiretorio">
                @foreach($usuarios as $user)
                    <span class="user-chip" data-user-chip><strong>{{ $user->name }}</strong><span class="tag">{{ $user->role === 'coordenador' ? 'Coordenação' : 'Professor' }}</span></span>
                @endforeach
            </div>
        </div>
    </details>

    <section class="section">
        <div class="section-heading"><div><h2>HAEs do semestre</h2><p>Use Ctrl ou Cmd para selecionar mais de um relator.</p></div><span class="count-badge">{{ $haes->count() }}</span></div>
        <div class="reviewer-grid">
            @forelse($haes as $hae)
                <article class="reviewer-card">
                    <div class="reviewer-card__head"><div><h3>{{ $hae->titulo }}</h3><small>{{ $hae->user->name ?? 'Professor não informado' }}</small></div><a href="{{ route('hae.show', $hae->id) }}" class="text-link">Ver HAE</a></div>
                    <div class="reviewer-card__tags">
                        @forelse($hae->relatores as $relator)<span class="tag">{{ $relator->name }}</span>@empty<span class="tag tag--warning">Sem relator</span>@endforelse
                    </div>
                    <form method="POST" action="{{ route('direcao.relatores.update', $hae->id) }}">
                        @csrf
                        <div class="field"><label for="relatores-{{ $hae->id }}">Relatores responsáveis</label><select id="relatores-{{ $hae->id }}" name="relatores[]" multiple size="5">
                            @foreach($usuarios as $user)<option value="{{ $user->id }}" {{ $hae->relatores->contains($user->id) ? 'selected' : '' }}>{{ $user->name }} · {{ $user->role === 'coordenador' ? 'Coordenação' : 'Professor' }}</option>@endforeach
                        </select></div>
                        <button type="submit" class="button button--small">Salvar atribuição</button>
                    </form>
                </article>
            @empty
                <div class="panel panel__body"><div class="empty-state"><p>Nenhuma HAE encontrada no semestre ativo.</p></div></div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('buscarUsuario')?.addEventListener('input', (event) => {
            const term = event.target.value.toLocaleLowerCase('pt-BR');
            document.querySelectorAll('[data-user-chip]').forEach((item) => item.hidden = !item.textContent.toLocaleLowerCase('pt-BR').includes(term));
        });
    </script>
@endpush
