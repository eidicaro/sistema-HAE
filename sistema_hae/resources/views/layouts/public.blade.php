<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema HAE') · Fatec Tatuí</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="public-shell">
    <header class="public-header">
        <a href="/" class="public-brand" aria-label="Página inicial do Sistema HAE">
            <img src="{{ asset('images/20_anos.png') }}" alt="Fatec Tatuí">
            <span></span>
            <strong>Sistema HAE</strong>
        </a>
        <img src="{{ asset('images/cps_sp.png') }}" alt="Centro Paula Souza e Governo do Estado de São Paulo" class="public-header__cps">
    </header>

    <main class="public-main">
        @include('components.flash-messages')
        @yield('content')
    </main>

    <footer class="public-footer">
        <span>Fatec Tatuí</span>
        <span>Centro Paula Souza · Governo do Estado de São Paulo</span>
    </footer>
</body>
</html>
