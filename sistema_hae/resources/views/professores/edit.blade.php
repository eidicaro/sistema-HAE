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

    <h1>Editar Professor</h1>

    @if ($errors->any())

    <div class="error">

        <ul>

            @foreach ($errors->all() as $erro)
            <li>{{ $erro }}</li>
            @endforeach

        </ul>

    </div>

    @endif

    <form action="{{ route('direcao.professores.update', $professor) }}" method="POST" class="form-professor">
        @csrf
        @method('PUT')

        <label> Nome </label>

        <br>

        <input type="text" name="name" value="{{ old('name',$professor->name) }}" required>

        <br><br>

        <label>Email</label>

        <br>

        <input type="email" name="email" value="{{ old('email',$professor->email) }}" required>

        <br><br>

        <label>Senha</label>

        <br>

        <input type="password" name="password">

        <br><br>

        <label>Confirmar Senha</label>

        <br>

        <input type="password" name="password_confirmation">

        <br><br>

        <small>Deixe a senha em branco caso não deseje alterá-la.</small>

        <br><br>

        <button type="submit" class="btn-salvar">Salvar Alterações</button>
    </form>

    <br>

    <a href="/direcao/professores" class="btn-voltar">Voltar</a>

</body>

</html>
