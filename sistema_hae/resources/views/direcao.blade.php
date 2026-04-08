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

    <h1>Olá Direção!</h1>
    <form method="POST" action="/logout" >
    @csrf
    <button type="submit" class="logout">Logout</button>
    </form>

    <h2>HAEs Submetidas</h2>
    <a href="/direcao/relatores" class="btn-results">Ver Relatores</a>

    @include('components.exibir-hae')
    <a href="/resultados-dir" class="btn-results">Ver Resultados</a>

    <h2>Controle de Carga Horária</h2>

        <table cellpadding="10" style="" class="carga-hora">
            <tr>
                <th>Tipo</th>
                <th>Limite</th>
                <th>Usado</th>
                <th>Restante</th>
            </tr>

            @foreach($dadosLimites as $dado)
                <tr>
                    <td>{{ ucfirst($dado['tipo']) }}</td>
                    <td>{{ $dado['limite'] }}h</td>
                    <td>{{ $dado['usado'] }}h</td>

                    <td style="
                        color: {{ $dado['restante'] < 0 ? 'red' : 'green' }};
                        font-weight: bold;
                    ">
                        {{ $dado['restante'] }}h
                    </td>
                </tr>
            @endforeach
        </table>

    <h2>Definir Limite de Carga Horária</h2>

        <form method="POST" action="{{ route('direcao.limites.salvar') }}" class="definir-hora">
            @csrf

            <label>Tipo de HAE</label>
            <select name="tipo">
                <option value="ams">AMS</option>
                <option value="graduacao">Graduação</option>
                <option value="administracao">Administração</option>
                <option value="estudos">Estudos</option>
                <option value="extensao">Extensão</option>
                <option value="plantao">Plantão</option>
            </select>

            <label>Carga Horária Total</label>
            <input type="number" name="carga_total" required>

            <button type="submit">Salvar Limite</button>
        </form>

        <div class="semestre">
            <h2>Semestre atual: {{ $semestreAtual->nome ?? 'Nenhum ativo' }}</h2>
            <a href="/semestres" class="btn-semestres">Gerenciar Semestres</a>
        </div>
</body>
</html>