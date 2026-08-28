@extends('layouts.app')

@section('title', 'Novo professor')
@section('eyebrow', 'Gestão de usuários')
@section('page-title', 'Cadastrar professor')
@section('page-subtitle', 'Crie as credenciais de acesso para um novo professor.')

@section('header-actions')<a href="{{ route('direcao.professores.index') }}" class="button button--secondary">← Voltar</a>@endsection

@section('content')
    <form action="{{ route('direcao.professores.store') }}" method="POST" class="form-card form-card--narrow">
        @csrf
        <div class="form-card__intro"><h2>Dados do professor</h2><p>O novo usuário será criado com o perfil de professor.</p></div>
        <section class="form-section"><div class="form-grid">
            <div class="field field--full"><label for="name">Nome completo</label><input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"></div>
            <div class="field field--full"><label for="email">E-mail</label><input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"></div>
            <div class="field"><label for="password">Senha inicial</label><input type="password" id="password" name="password" required autocomplete="new-password"><span class="field__hint">Mínimo de 6 caracteres.</span></div>
            <div class="field"><label for="password_confirmation">Confirmar senha</label><input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"></div>
        </div></section>
        <div class="form-actions"><a href="{{ route('direcao.professores.index') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">Cadastrar professor</button></div>
    </form>
@endsection
