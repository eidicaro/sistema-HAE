@extends('layouts.app')

@section('title', 'Editar professor')
@section('eyebrow', 'Gestão de usuários')
@section('page-title', 'Editar professor')
@section('page-subtitle', 'Atualize os dados de acesso de ' . $professor->name . '.')

@section('header-actions')<a href="{{ route('direcao.professores.index') }}" class="button button--secondary">← Voltar</a>@endsection

@section('content')
    <form action="{{ route('direcao.professores.update', $professor) }}" method="POST" class="form-card form-card--narrow">
        @csrf @method('PUT')
        <div class="form-card__intro"><h2>Dados do professor</h2><p>Altere somente os campos que precisam ser atualizados.</p></div>
        <section class="form-section"><div class="form-grid">
            <div class="field field--full"><label for="name">Nome completo</label><input type="text" id="name" name="name" value="{{ old('name', $professor->name) }}" required autocomplete="name"></div>
            <div class="field field--full"><label for="email">E-mail</label><input type="email" id="email" name="email" value="{{ old('email', $professor->email) }}" required autocomplete="email"></div>
            <div class="field"><label for="password">Nova senha</label><input type="password" id="password" name="password" autocomplete="new-password"><span class="field__hint">Deixe em branco para manter a senha atual.</span></div>
            <div class="field"><label for="password_confirmation">Confirmar nova senha</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"></div>
        </div></section>
        <div class="form-actions"><a href="{{ route('direcao.professores.index') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">Salvar alterações</button></div>
    </form>
@endsection
