<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('../css/create-rel.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">

</head>
<body>
    

<h1>Relatório da HAE</h1>

<h2>{{ $hae->titulo }}</h2>

<form method="POST" action="/hae/{{ $hae->id }}/relatorio" enctype="multipart/form-data">
    @csrf

    <h2>📌 Informações Gerais</h2>

    <label>Título do Relatório</label>
    <input type="text" name="titulo">

    <label>Sumário Executivo</label>
    <textarea name="sumario"></textarea>

    <label>Principais Resultados</label>
    <textarea name="resultados_texto"></textarea>

    <hr>

    <h2>📎 Upload</h2>

    <input type="file" name="arquivo_principal">
    <input type="file" name="comprovacoes[]" multiple>

    <br><br>
    <button type="submit">Enviar</button>
</form>

</body>
</html>