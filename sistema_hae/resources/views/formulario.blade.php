<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova HAE</title>
    <link rel="stylesheet" href="{{ asset('../css/formulario.css') }}">
</head>
<body>
    <!-- não vou comentar tudo isso não, so o basico que precisa saber 
     Boa Sorte Dev do futuro :)-->
    <!-- caro dev, o haecontroller é o principal, a maioria dos parametros estão sendo passados por ele -->


<h1>Nova HAE</h1>
<a href="/professor">voltar</a>

@php
    $tipo = request('tipo');
@endphp

<form method="POST" action="{{ isset($hae) ? route('hae.update', $hae->id) : '/salvar-hae' }}">
    @csrf

    @if(isset($hae))
        @method('PUT')
    @endif

<!-- ================= FIXO ================= -->

<h2>Sobre o EDITAL DE HAE Nº 02/2025 – 1º SEMESTRE DE 2026</h2>

<label>Sobre o EDITAL DE HAE Nº 02/2025 – 1º SEMESTRE DE 2026</label>
<select name="edital">
    <option value="1" {{ old('edital', $hae->edital_aceito ?? '') == 1 ? 'selected' : '' }}>Li e estou de acordo</option>
    <option value="0" {{ old('edital', $hae->edital_aceito ?? '') == 0 ? 'selected' : '' }}>Não desejo submeter</option>
</select>

<h2>Das Informações Iniciais</h2>

<!-- <label>Nome do Professor</label> -->

<div class="mb-3">
    <label for="tipo_hae_id" class="form-label">Tipo de HAE</label>

    <select class="form-select" id="tipo_hae_id" name="tipo_hae_id" required>
        <option value="">Selecione...</option>

        @foreach($nomes as $tipo)
            <option value="{{ $tipo->id }}"
                {{ old('tipo_hae_id', $hae->tipo_hae_id ?? '') == $tipo->id ? 'selected' : '' }}>
                {{ $tipo->nome }}
            </option>
        @endforeach
    </select>
</div>

<label>Curso com maior aderência ao projeto</label>
<select name="curso">
    <option {{ old('curso', $hae->curso ?? '') == 'Automação Industrial' ? 'selected' : '' }}>Automação Industrial</option>
    <option {{ old('curso', $hae->curso ?? '') == 'Manutenção Industrial' ? 'selected' : '' }}>Manutenção Industrial</option>
    <option {{ old('curso', $hae->curso ?? '') == 'Gestão Empresarial' ? 'selected' : '' }}>Gestão Empresarial</option>
    <option {{ old('curso', $hae->curso ?? '') == 'Gestão da Tecnologia da Informação' ? 'selected' : '' }}>Gestão da Tecnologia da Informação</option>
    <option {{ old('curso', $hae->curso ?? '') == 'Produção Fonográfica' ? 'selected' : '' }}>Produção Fonográfica</option>
    <option {{ old('curso', $hae->curso ?? '') == 'AMS - Análise e Desenvolvimento de Sistemas' ? 'selected' : '' }}>AMS - Análise e Desenvolvimento de Sistemas</option>
    <option {{ old('curso', $hae->curso ?? '') == 'AMS - Processos Gerenciais' ? 'selected' : '' }}>AMS - Processos Gerenciais</option>
</select>


<label>Titulo do Projeto</label>
<input type="text" name="titulo" value="{{ old('titulo', $hae->titulo ?? '') }}">

<label>Carga Horaria Semanal Solicitada</label>
<input type="number" name="carga_horaria" value="{{ old('carga_horaria', $hae->carga_horaria ?? '') }}">

<label>Resumo do Projeto</label>
<textarea name="resumo">{{ old('resumo', $hae->resumo ?? '') }}</textarea>

<label>Justificativa do Projeto</label>
<textarea name="justificativa">{{ old('justificativa', $hae->justificativa ?? '') }}</textarea>

<div class="mb-3">
    <label for="especificacoes" class="form-label">
        Especificações da HAE
    </label>

    <textarea
        class="form-control"
        id="especificacoes"
        name="especificacoes"
        rows="4"
        placeholder="Caso esta HAE possua alguma especificação ou detalhe importante, informe aqui.">{{ old('especificacoes', $hae->especificacoes ?? '') }}</textarea>

    <small class="text-muted">
        Campo opcional.
    </small>
</div>

<h2>Do Cronograma</h2>

<small style="color: #666;">
    Informe os dias e horários das atividades.<br>
    Exemplo: Segundas e Quartas - 19h às 21h
</small>

<textarea 
    name="cronograma" 
    rows="4" 
    placeholder="Ex: Segundas e Quartas das 19h às 21h">
{{ old('cronograma', $hae->cronograma ?? '') }}
</textarea>

<input type="hidden" name="tipo" value="{{ $tipo }}">

<br><br>
<button type="submit">Enviar</button>

</form>

@if ($errors->any())
    <div style="color:red; margin-bottom:20px;">
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif


</body>
</html>