@extends('layouts.app')

@section('content')
<style>
    .th-container { max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
    .th-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .th-header h1 { color: #b20000; font-size: 1.5rem; margin: 0; }
    .th-btn { background: #b20000; color: #fff; border: none; padding: 0.55rem 1.1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; cursor: pointer; display: inline-block; }
    .th-btn:hover { background: #8f0000; color: #fff; }
    .th-btn-outline { background: #fff; color: #b20000; border: 1px solid #b20000; }
    .th-btn-outline:hover { background: #fdeaea; }
    .th-card { background: #fff; border: 1px solid #e5e5e5; border-radius: 10px; overflow: hidden; }
    table.th-table { width: 100%; border-collapse: collapse; }
    table.th-table th { background: #b20000; color: #fff; text-align: left; padding: 0.75rem 1rem; font-size: 0.85rem; }
    table.th-table td { padding: 0.75rem 1rem; border-top: 1px solid #eee; font-size: 0.9rem; vertical-align: middle; }
    .th-badge { padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .th-badge-ativo { background: #e6f4ea; color: #1e7e34; }
    .th-badge-inativo { background: #f2f2f2; color: #777; }
    .th-actions { display: flex; gap: 0.5rem; }
    .th-actions form { display: inline; }
    .th-actions button { border: none; background: none; cursor: pointer; font-size: 0.85rem; color: #b20000; }
    .th-alert { padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
    .th-alert-success { background: #e6f4ea; color: #1e7e34; }
    .th-alert-error { background: #fdeaea; color: #b20000; }
</style>

<div class="th-container">
    <div class="th-header">
        <h1>Tipos de HAE</h1>
        <a href="{{ route('direcao.tipos-hae.create') }}" class="th-btn">+ Novo Tipo</a>
    </div>

    @if (session('success'))
        <div class="th-alert th-alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="th-alert th-alert-error">{{ session('error') }}</div>
    @endif

    <div class="th-card">
        <table class="th-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Limite (h)</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tipos as $tipo)
                    <tr>
                        <td>{{ $tipo->nome }}</td>
                        <td>{{ $tipo->descricao ?? '—' }}</td>
                        <td>{{ $tipo->limite }}</td>
                        <td>
                            <span class="th-badge {{ $tipo->ativo ? 'th-badge-ativo' : 'th-badge-inativo' }}">
                                {{ $tipo->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div class="th-actions">
                                <a href="{{ route('direcao.tipos-hae.edit', $tipo) }}">Editar</a>

                                <form action="{{ route('direcao.tipos-hae.toggle', $tipo) }}" method="POST">
                                    @csrf
                                    <button type="submit">{{ $tipo->ativo ? 'Desativar' : 'Ativar' }}</button>
                                </form>

                                <form action="{{ route('direcao.tipos-hae.destroy', $tipo) }}" method="POST"
                                      onsubmit="return confirm('Excluir este tipo de HAE?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 1.5rem; color:#999;">
                            Nenhum tipo de HAE cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection