@extends('layouts.app')

@section('title', 'Editar tipo de HAE')
@section('eyebrow', 'Configuração institucional')
@section('page-title', 'Editar tipo de HAE')
@section('page-subtitle', 'Atualize a categoria ' . $tipoHae->nome . ' e sua capacidade.')
@section('header-actions')<a href="{{ route('direcao.tipos-hae.index') }}" class="button button--secondary">← Voltar</a>@endsection

@section('content')
    <form action="{{ route('direcao.tipos-hae.update', $tipoHae) }}" method="POST" class="form-card form-card--narrow">
        @csrf @method('PUT')
        <div class="form-card__intro"><h2>Dados do tipo</h2><p>Alterações de limite afetam as validações do semestre atual.</p></div>
        <section class="form-section">@include('direcao.tipoHae._form')</section>
        <div class="form-actions"><a href="{{ route('direcao.tipos-hae.index') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">Salvar alterações</button></div>
    </form>

    <section class="panel" style="margin-top: 24px">
        <div class="panel__header"><div><h2>Subtipos de {{ $tipoHae->nome }}</h2><p>Todos consomem o limite de {{ $tipoHae->limite }}h do tipo pai.</p></div><span class="count-badge">{{ $tipoHae->subtipos->count() }}</span></div>
        <div class="panel__body">
            <form action="{{ route('direcao.tipos-hae.subtipos.store', $tipoHae) }}" method="POST" class="form-grid">
                @csrf
                <div class="field"><label for="novo-subtipo-nome">Nome do novo subtipo</label><input type="text" id="novo-subtipo-nome" name="nome" value="{{ old('nome') }}" maxlength="255" required></div>
                <div class="field"><label for="novo-subtipo-descricao">Descrição</label><input type="text" id="novo-subtipo-descricao" name="descricao" value="{{ old('descricao') }}" maxlength="10000"></div>
                <div class="field field--full"><div class="checkbox-card"><input type="checkbox" id="novo-subtipo-ativo" name="ativo" value="1" checked><label for="novo-subtipo-ativo">Disponibilizar para novas submissões</label></div></div>
                <div class="form-actions field--full"><button type="submit" class="button">Adicionar subtipo</button></div>
            </form>
        </div>
    </section>

    @foreach($tipoHae->subtipos as $subtipo)
        <section class="panel" style="margin-top: 16px">
            <div class="panel__body">
                <form action="{{ route('direcao.tipos-hae.subtipos.update', [$tipoHae, $subtipo]) }}" method="POST" class="form-grid">
                    @csrf @method('PUT')
                    <div class="field"><label for="subtipo-nome-{{ $subtipo->id }}">Nome</label><input type="text" id="subtipo-nome-{{ $subtipo->id }}" name="nome" value="{{ $subtipo->nome }}" maxlength="255" required></div>
                    <div class="field"><label for="subtipo-descricao-{{ $subtipo->id }}">Descrição</label><input type="text" id="subtipo-descricao-{{ $subtipo->id }}" name="descricao" value="{{ $subtipo->descricao }}" maxlength="10000"></div>
                    <div class="field field--full"><div class="checkbox-card"><input type="checkbox" id="subtipo-ativo-{{ $subtipo->id }}" name="ativo" value="1" {{ $subtipo->ativo ? 'checked' : '' }}><label for="subtipo-ativo-{{ $subtipo->id }}">Subtipo ativo</label></div></div>
                    <div class="form-actions field--full"><button type="submit" class="button button--secondary">Salvar subtipo</button></div>
                </form>
                <div class="data-table__actions" style="margin-top: 12px">
                    <form action="{{ route('direcao.tipos-hae.subtipos.toggle', [$tipoHae, $subtipo]) }}" method="POST">@csrf<button type="submit" class="button button--ghost button--small">{{ $subtipo->ativo ? 'Desativar' : 'Ativar' }}</button></form>
                    <form action="{{ route('direcao.tipos-hae.subtipos.destroy', [$tipoHae, $subtipo]) }}" method="POST" data-confirm="Deseja realmente excluir este subtipo?">@csrf @method('DELETE')<button type="submit" class="button button--ghost button--small">Excluir</button></form>
                </div>
            </div>
        </section>
    @endforeach
@endsection
