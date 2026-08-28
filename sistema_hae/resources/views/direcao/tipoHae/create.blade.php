@extends('layouts.app')

@section('title', 'Novo tipo de HAE')
@section('eyebrow', 'Configuração institucional')
@section('page-title', 'Novo tipo de HAE')
@section('page-subtitle', 'Crie uma categoria e defina sua capacidade para cada semestre.')
@section('header-actions')<a href="{{ route('direcao.tipos-hae.index') }}" class="button button--secondary">← Voltar</a>@endsection

@section('content')
    <form action="{{ route('direcao.tipos-hae.store') }}" method="POST" class="form-card form-card--narrow">
        @csrf
        <div class="form-card__intro"><h2>Dados do tipo</h2><p>O nome identifica a categoria em formulários, painéis e relatórios.</p></div>
        <section class="form-section">@include('direcao.tipoHae._form')</section>
        <div class="form-actions"><a href="{{ route('direcao.tipos-hae.index') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">Criar tipo</button></div>
    </form>
@endsection
