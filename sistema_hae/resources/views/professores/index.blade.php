<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professores</title>

    <link rel="stylesheet" href="{{ asset('/css/crud-professores.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fonte.css') }}">
</head>
<body>

@include('components.header')

<h1>Gerenciar Professores</h1>

<a href="/direcao" class="btn-voltar">Voltar</a>

@if(session('sucesso'))
    <div class="success">
        {{ session('sucesso') }}
    </div>
@endif

@if(session('error'))
    <div class="error">
        {{ session('error') }}
    </div>
@endif

<div class="container-crud">
<form method="GET" action="/direcao/professores" class="busca-professor">
        <input type="text" name="busca" value="{{ $busca }}" placeholder="Pesquisar por nome ou e-mail">
        <button type="submit">Pesquisar</button>

    </form>
    <br>

    <a href="/direcao/professores/create" class="btn-novo">Novo Professor</a>

    <br><br>

    <table class="tabela-professores">
        <tr class="tr-nomes">
            <th>Nome</th>
            <th>E-mail</th>
            <th>Ações</th>
        </tr>

        @forelse($professores as $professor)

            <tr>

                <td>{{ $professor->name }}</td>
                <td>{{ $professor->email }}</td>

                <td>
                    <a href="/direcao/professores/{{ $professor->id }}/edit" class="btn-editar">Editar</a>

                    <form action="/direcao/professores/{{ $professor->id }}" method="POST" style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button onclick="return confirm('Deseja excluir este professor?')" class="btn-excluir">Excluir</button>
                    </form>
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="3">Nenhum professor encontrado.</td>
            </tr>

        @endforelse

    </table>

</div>
<br>


</body>
</html>