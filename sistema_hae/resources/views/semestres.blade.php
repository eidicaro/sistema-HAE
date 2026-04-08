<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Semestres</title>
    <link rel="stylesheet" href="{{ asset('../css/semestres.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
</head>

<body style="font-family: Arial; padding:20px;">
<a href="{{ route($user->role) }}">Voltar</a>

<h1 style="color:#B30A07;">Gerenciar Semestres</h1>

<!-- MENSAGEM -->
@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

<!-- FORM CRIAR -->
 <div class="criar-semestre">
    <h3>Criar novo semestre</h3>

    <form method="POST" action="/semestres" class="form-semestre">
        @csrf

        <input type="text" name="nome" placeholder="Ex: 2026/1" required class="nome">

        <label>Início:</label>
        <input type="date" name="data_inicio" required class="data">

        <label>Fim:</label>
        <input type="date" name="data_fim" required class="data">

        <button class="btn-criar">Criar</button>
    </form>
</div>

<hr>

<!-- LISTAGEM -->
<h3>Semestres cadastrados</h3>

<table  class="semestres">
    <tr>
        <th>Nome</th>
        <th>Período</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>

    @foreach($semestres as $semestre)
    <tr>
        <td>{{ $semestre->nome }}</td>
        <td>{{ $semestre->data_inicio }} até {{ $semestre->data_fim }}</td>
        <td>
            @if($semestre->ativo)
                <strong style="color:green;">ATIVO</strong>
            @else
                Inativo
            @endif
        </td>
        <td>
            @if(!$semestre->ativo)
                <form method="POST" action="/semestres/{{ $semestre->id }}/ativar">
                    @csrf
                    <button>Ativar</button>
                </form>
            @endif
        </td>
    </tr>
    @endforeach

</table>

</body>
</html>