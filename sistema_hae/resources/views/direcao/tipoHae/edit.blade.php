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
@endsection
