@extends('layouts.app')

@section('title', 'Semestres')
@section('eyebrow', 'Configuração institucional')
@section('page-title', 'Gerenciar semestres')
@section('page-subtitle', 'Cadastre os períodos letivos e defina qual está recebendo novas submissões.')

@section('content')
    <div class="detail-layout">
        <section class="form-card form-card--narrow">
            <div class="form-card__intro"><h2>Novo semestre</h2><p>Informe o nome e o intervalo do período letivo.</p></div>
            <form method="POST" action="{{ route('semestres.store') }}">
                @csrf
                <div class="form-section"><div class="form-grid">
                    <div class="field field--full"><label for="nome">Identificação</label><input type="text" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Ex.: 2026/1" required></div>
                    <div class="field"><label for="data_inicio">Data de início</label><input type="date" id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" required></div>
                    <div class="field"><label for="data_fim">Data de término</label><input type="date" id="data_fim" name="data_fim" value="{{ old('data_fim') }}" required></div>
                </div></div>
                <div class="form-actions"><button type="submit" class="button">Cadastrar semestre</button></div>
            </form>
        </section>

        <section class="panel">
            <div class="panel__header"><div><h2>Períodos cadastrados</h2></div><span class="count-badge">{{ $semestres->count() }}</span></div>
            <div class="table-wrap"><table class="data-table">
                <thead><tr><th>Semestre</th><th>Período</th><th>Status</th><th>Ação</th></tr></thead>
                <tbody>
                    @forelse($semestres as $semestre)
                        <tr>
                            <td><strong>{{ $semestre->nome }}</strong></td>
                            <td>{{ $semestre->data_inicio->format('d/m/Y') }} a {{ $semestre->data_fim->format('d/m/Y') }}</td>
                            <td><span class="tag {{ $semestre->ativo ? 'tag--active' : 'tag--inactive' }}">{{ $semestre->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                            <td>@unless($semestre->ativo)<form method="POST" action="{{ route('semestres.ativar', $semestre->id) }}">@csrf<button class="button button--secondary button--small">Ativar</button></form>@endunless</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-state"><p>Nenhum semestre cadastrado.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </section>
    </div>
@endsection
