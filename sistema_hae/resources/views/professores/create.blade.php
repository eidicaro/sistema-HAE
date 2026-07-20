<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title>Novo Professor</title>

    <link rel="stylesheet" href="{{ asset('/css/crud-professores.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fonte.css') }}">
</head>
<body>

@include('components.header')

<h1>Novo Professor</h1>

@if ($errors->any())

<div class="error">

    <ul>

        @foreach ($errors->all() as $erro)

            <li>{{ $erro }}</li>

        @endforeach

    </ul>

</div>

@endif

<form action="/direcao/professores" method="POST" class="form-professor">
    @csrf

    <label>Nome</label>

    <br>

    <input type="text" name="name" value="{{ old('name') }}" required>

    <br><br>

    <label>Email</label>

    <br>

    <input type="email" name="email" value="{{ old('email') }}" required>

    <br><br>

    <label>Senha</label>

    <br>

    <input type="password" name="password" required>

    <br><br>

    <label>Confirmar Senha</label>

    <br>

    <input type="password" name="password_confirmation" required>

    <br><br>

    <button type="submit" class="btn-salvar">Cadastrar</button>

</form>

<br>

<a href="/direcao/professores" class="btn-voltar">Voltar</a>

</body>
</html>