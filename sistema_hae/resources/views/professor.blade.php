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

    <h1>Olá Professor!</h1>
    <form method="POST" action="/logout" >
    @csrf
    <button type="submit" class="logout">Logout</button>
</form>

    <div class="nova-hae">
        <label for="tipoHAE">Nova HAE</label>

        <select id="tipoHAE" class="select-hae">
            <option value="">Escolha o tipo de HAE</option>
            <option value="graduacao">Projeto de Graduação</option>
            <option value="administracao">Administração Acadêmica</option>
            <option value="estudos">Estudos e Projetos</option>
            <option value="extensao">Extensão</option>
            <option value="plantao">Plantão Didático</option>
            <option value="ams">Programa AMS</option>
        </select>
    </div>

    <h2>Minhas HAEs</h2>
    @include('components.exibir-hae')
    @include('components.exibir-hae2')

    <script>
        document.getElementById("tipoHAE").addEventListener("change", function () {
            let tipo = this.value;

            if (tipo) {
                window.location.href = `/formulario?tipo=${tipo}`;
            }
        });
    </script>

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