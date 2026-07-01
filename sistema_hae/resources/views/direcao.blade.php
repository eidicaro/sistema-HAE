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
    <!-- caro dev, o haecontroller é o principal, a maioria dos parametros estão sendo passados por ele -->
    @include('components.header')

    <h1>Olá Direção!</h1>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="logout">Logout</button>
    </form>

    <section>
        <h2>HAEs Submetidas</h2>
        <a href="/direcao/relatores" class="btn-results">Ver Relatores</a>

        @include('components.exibir-hae')
        <a href="/resultados-dir" class="btn-results">Ver Resultados</a>
    </section>

    <section>
        <h2>Controle de Carga Horária</h2>

        <table cellpadding="10" class="carga-hora">
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
                    <td style="color: {{ $dado['restante'] < 0 ? 'red' : 'green' }}; font-weight: bold;">
                        {{ $dado['restante'] }}h
                    </td>
                </tr>
            @endforeach
        </table>

        <a href="{{ route('direcao.tipos-hae.index') }}" class="btn-results">Gerenciar Tipos de HAE</a>
    </section>

    <!-- semestre -->
    <section class="semestre">
        <h2>Semestre atual: {{ $semestreAtual->nome ?? 'Nenhum ativo' }}</h2>
        <a href="/semestres" class="btn-semestres">Gerenciar Semestres</a>
    </section>
</body>
</html>