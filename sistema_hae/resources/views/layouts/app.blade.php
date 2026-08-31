<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema HAE') · Fatec Tatuí</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="app-shell">
    @php($usuarioAtual = auth()->user())

    <aside class="sidebar" id="appSidebar" aria-label="Navegação principal">
        <a href="{{ route($usuarioAtual->role) }}" class="brand" aria-label="Sistema HAE - início">
            <img src="{{ asset('images/20_anos.png') }}" alt="Fatec Tatuí" class="brand__fatec">
            <span class="brand__divider"></span>
            <span class="brand__product">Sistema <strong>HAE</strong></span>
        </a>

        <nav class="sidebar__nav">
            <p class="sidebar__label">Navegação</p>

            <a href="{{ route($usuarioAtual->role) }}" class="nav-item {{ request()->routeIs($usuarioAtual->role) ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/></svg>
                Visão geral
            </a>

            @if($usuarioAtual->role === 'professor')
                <a href="{{ route('hae.create') }}" class="nav-item {{ request()->routeIs('hae.create') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2Z"/></svg>
                    Nova HAE
                </a>
            @endif

            @if($usuarioAtual->role === 'direcao')
                <p class="sidebar__label sidebar__label--spaced">Gestão</p>

                <a href="{{ route('direcao.relatores') }}" class="nav-item {{ request()->routeIs('direcao.relatores*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3Zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"/></svg>
                    Relatores
                </a>
                <a href="{{ route('direcao.professores.index') }}" class="nav-item {{ request()->routeIs('direcao.professores.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z"/></svg>
                    Professores
                </a>
                <a href="{{ route('direcao.tipos-hae.index') }}" class="nav-item {{ request()->routeIs('direcao.tipos-hae.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m17.63 5.84.95-.95a1 1 0 0 0-1.41-1.41l-.95.95A8 8 0 1 0 19.57 7.8l.95-.95a1 1 0 1 0-1.41-1.41l-.95.95a8.03 8.03 0 0 0-.53-.55ZM12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12Zm0-9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/></svg>
                    Tipos e limites
                </a>
                <a href="{{ route('semestres.index') }}" class="nav-item {{ request()->routeIs('semestres.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8h16v8Zm0-10H4V7a1 1 0 0 1 1-1h1v2h2V6h8v2h2V6h1a1 1 0 0 1 1 1v2Z"/></svg>
                    Semestres
                </a>
                <a href="{{ route('direcao.resultados') }}" class="nav-item {{ request()->routeIs('direcao.resultados') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16v2H4v-2Zm1-2h3V9H5v8Zm5 0h3V3h-3v14Zm5 0h3v-6h-3v6Z"/></svg>
                    Resultados
                </a>
            @endif
        </nav>

        <div class="sidebar__footer">
            <div class="user-summary">
                <span class="user-summary__avatar">{{ mb_strtoupper(mb_substr($usuarioAtual->name, 0, 1)) }}</span>
                <span class="user-summary__text">
                    <strong>{{ $usuarioAtual->name }}</strong>
                    <small>{{ match($usuarioAtual->role) { 'direcao' => 'Direção', 'coordenador' => 'Coordenação', default => 'Professor' } }}</small>
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button" title="Sair do sistema">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17v-2h4V9h-4V7l-5 5 5 5Zm9-14h-8a2 2 0 0 0-2 2v1h2V5h8v14h-8v-1H9v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Z"/></svg>
                    <span>Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <button type="button" class="sidebar-overlay" id="sidebarOverlay" aria-label="Fechar menu"></button>

    <div class="app-content">
        <header class="topbar">
            <button type="button" class="menu-button" id="menuButton" aria-controls="appSidebar" aria-expanded="false">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v2H3V6Zm0 5h18v2H3v-2Zm0 5h18v2H3v-2Z"/></svg>
                <span class="sr-only">Abrir menu</span>
            </button>
            <div class="topbar__heading">
                <p class="eyebrow">@yield('eyebrow', 'Sistema de gestão acadêmica')</p>
                <h1>@yield('page-title', 'Sistema HAE')</h1>
                @hasSection('page-subtitle')
                    <p class="topbar__subtitle">@yield('page-subtitle')</p>
                @endif
            </div>
            <div class="topbar__actions">
                @yield('header-actions')
            </div>
        </header>

        <main class="main-content">
            @include('components.flash-messages')
            @yield('content')
        </main>
    </div>

</body>
</html>
