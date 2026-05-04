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

        <div class="usuarios-header">
            <h2 class="titulo-secao">Usuários disponíveis</h2>

            <button onclick="toggleUsuarios()" class="btn-toggle">
                Mostrar usuários ⬇
            </button>
        </div>

        <div id="usuariosContainer" style="display:none;">
            
            <input 
                type="text" 
                id="buscarUsuario" 
                placeholder="Buscar professor..."
                onkeyup="filtrarUsuarios()"
                class="input-busca"
            />

            <div class="usuarios-lista">
                @foreach($usuarios as $user)
                    <div class="usuario-item">
                        {{ $user->name }}
                        <span class="role">({{ $user->role }})</span>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="usuarios-lista" id="usuariosLista" style="display:none;">
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

    <script>
        function toggleUsuarios() {
            const container = document.getElementById('usuariosContainer');
            const btn = document.querySelector('.btn-toggle');

            if (container.style.display === 'none') {
                container.style.display = 'block';
                btn.textContent = 'Ocultar usuários ⬆';
            } else {
                container.style.display = 'none';
                btn.textContent = 'Mostrar usuários ⬇';
            }
        }


        function filtrarUsuarios() {
            let input = document.getElementById('buscarUsuario');
            let filtro = input.value.toLowerCase();
            let itens = document.querySelectorAll('.usuario-item');

            itens.forEach(item => {
                let texto = item.textContent.toLowerCase();
                item.style.display = texto.includes(filtro) ? '' : 'none';
            });
        }
    </script>
</body>
</html>