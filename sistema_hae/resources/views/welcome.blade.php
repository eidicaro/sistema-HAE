@extends('layouts.public')

@section('title', 'Acesso ao Sistema HAE')

@section('content')
    <section class="welcome-hero">
        <div class="welcome-hero__copy">
            <p class="eyebrow">Fatec Tatuí · Gestão acadêmica</p>
            <h1>Atividades HAE,<br><span>do planejamento ao resultado.</span></h1>
            <p>Um ambiente único para submissão, análise, acompanhamento e conclusão das Horas de Atividades Específicas.</p>
        </div>

        <div class="welcome-hero__aside">
            <h2>Acesse seu ambiente</h2>
            <p>Selecione o perfil correspondente à sua atuação.</p>

            <div class="profile-options">
                <a href="{{ route('login.show', 'professor') }}" class="profile-card">
                    <span class="profile-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 1 9l4 2.18V17l7 4 7-4v-5.82L21 10v7h2V9L12 3Zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9Z"/></svg></span>
                    <span><strong>Professor</strong><small>Submeter e acompanhar atividades</small></span><span class="profile-card__arrow">→</span>
                </a>
                <a href="{{ route('login.show', 'coordenador') }}" class="profile-card">
                    <span class="profile-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Z"/></svg></span>
                    <span><strong>Coordenação</strong><small>Analisar propostas e emitir pareceres</small></span><span class="profile-card__arrow">→</span>
                </a>
                <a href="{{ route('login.show', 'direcao') }}" class="profile-card">
                    <span class="profile-card__icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16v2H4v-2Zm1-2h3V9H5v8Zm5 0h3V3h-3v14Zm5 0h3v-6h-3v6Z"/></svg></span>
                    <span><strong>Direção</strong><small>Gerenciar, decidir e acompanhar resultados</small></span><span class="profile-card__arrow">→</span>
                </a>
            </div>
        </div>
    </section>
@endsection
