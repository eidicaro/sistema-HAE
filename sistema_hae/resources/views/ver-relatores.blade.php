<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>direção</title>
    <link rel="stylesheet" href="{{ asset('../css/direcao.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>
<body>
    @include('components.header')

    <a href="/direcao" class="btn-voltar">Voltar</a>
    
    <div class="container">

        <h2 class="titulo-secao">Usuários disponíveis</h2>

        <div class="usuarios-lista">
            @foreach($usuarios as $user)
                <div class="usuario-item">
                    {{ $user->name }}
                    <span class="role">({{ $user->role }})</span>
                </div>
            @endforeach
        </div>

        <h2 class="titulo-secao">HAEs</h2>

        @foreach($haes as $hae)
            <div class="hae-card">
                
                <h3 class="hae-titulo">{{ $hae->titulo }}</h3>

                <div class="relatores">
                    <strong>Relatores:</strong>

                    @forelse($hae->relatores as $relator)
                        <span class="tag">{{ $relator->name }}</span>
                    @empty
                        <span class="nenhum">Nenhum relator</span>
                    @endforelse
                </div>

                <form method="POST" action="/direcao/relatores/{{ $hae->id }}">
                    @csrf

                    <select name="relatores[]" multiple class="select-relatores">
                        @foreach($usuarios as $user)
                            <option value="{{ $user->id }}"
                                @if($hae->relatores->contains($user->id)) selected @endif
                            >
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn-salvar">
                        Salvar Relatores
                    </button>
                </form>

            </div>
        @endforeach

    </div>
</body>
</html>