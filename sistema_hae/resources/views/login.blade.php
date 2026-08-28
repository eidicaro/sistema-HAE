@extends('layouts.public')

@php
    $nomePerfil = match($tipo) { 'direcao' => 'Direção', 'coordenador' => 'Coordenação', default => 'Professor' };
@endphp

@section('title', "Acesso da {$nomePerfil}")

@section('content')
    <section class="login-page">
        <div class="login-card">
            <a href="/" class="login-card__back"><span aria-hidden="true">←</span> Voltar para seleção de perfil</a>
            <span class="login-card__badge">Acesso · {{ $nomePerfil }}</span>
            <h1>Bem-vindo de volta</h1>
            <p>Informe suas credenciais institucionais para continuar.</p>

            <form method="POST" action="/login/{{ $tipo }}">
                @csrf
                <div class="field">
                    <label for="email">E-mail ou usuário</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>
                </div>
                <button type="submit" class="button">Entrar no sistema</button>
            </form>
        </div>
    </section>
@endsection
