<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
    <link rel="stylesheet" href="{{ asset('../css/welcome.css') }}">

</head>

<body>

<div class="container">
    @include('components.header')
    
    <h1>Seja Bem Vindo!</h1>

    <div class="buttons">
        <a href="#" class="btn">Professor</a>
        <a href="#" class="btn">Coordenador</a>
        <a href="#" class="btn">Direção</a>
    </div>
</div>

</body>
</html>