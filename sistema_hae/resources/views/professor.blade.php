<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>professor</title>
    <link rel="stylesheet" href="{{ asset('../css/professor.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')
        <!-- caro dev, o haecontroller é o principal, a maioria dos parametros estão sendo passados por ele -->


    <h1>Olá Professor!</h1>
    <form method="POST" action="/logout" >
    @csrf
    <button type="submit" class="logout">Logout</button>
</form>

    <div class="nova-hae">
        <a href="{{ route('hae.create') }}" class="btn-create"> Nova HAE</a>
    </div>

    <h2>Minhas HAEs</h2>
    @include('components.exibir-hae')
    @include('components.exibir-hae2')

<!-- se ele for denominado como relator, as haes que ele precisa ver estão aqui -->
<h2>HAEs para Parecer</h2>

<div class="relator">
    <div class="hae-list">
        @forelse($haesRelator as $hae)
            <a href="/hae/{{ $hae->id }}" class="hae-item">
                <span class="titulo">{{ $hae->titulo }}</span>
                <span class="data">
                    data de submissão: {{ $hae->created_at->format('d/m/Y') }}
                </span>
            </a>
        @empty
            <p>Nenhuma HAE para parecer</p>
        @endforelse
    </div>
</div>
    
</body>
</html>