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

    <!-- ================= GRADUAÇÃO ================= -->
    @if($hae->tipo == 'graduacao')
        <h2>Projeto de Graduação</h2>

        <p>Orientações previstas: {{ $hae->graduacao->orientacoes }}</p>
        <input type="number" name="resultados[orientacoes][realizado]">
        <input type="hidden" name="resultados[orientacoes][previsto]" value="{{ $hae->graduacao->orientacoes }}">

        <p>Bancas (orientador): {{ $hae->graduacao->bancas_orientador }}</p>
        <input type="number" name="resultados[bancas_orientador][realizado]">
        <input type="hidden" name="resultados[bancas_orientador][previsto]" value="{{ $hae->graduacao->bancas_orientador }}">

        <p>Bancas (membro): {{ $hae->graduacao->bancas_membro }}</p>
        <input type="number" name="resultados[bancas_membro][realizado]">
        <input type="hidden" name="resultados[bancas_membro][previsto]" value="{{ $hae->graduacao->bancas_membro }}">

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->graduacao->indicador }}">
    @endif

    <!-- ================= ADMINISTRAÇÃO ================= -->
    @if($hae->tipo == 'administracao')
        <h2>Administração</h2>

        @if($hae->administracao->tipo_admin == 'avaliacao')
            <p>Encontros previstos: {{ $hae->administracao->encontros }}</p>
            <input type="number" name="resultados[encontros][realizado]">
            <input type="hidden" name="resultados[encontros][previsto]" value="{{ $hae->administracao->encontros }}">
        @endif

        @if($hae->administracao->tipo_admin == 'indicadores')
            <p>Relatórios previstos: {{ $hae->administracao->relatorios }}</p>
            <input type="number" name="resultados[relatorios][realizado]">
            <input type="hidden" name="resultados[relatorios][previsto]" value="{{ $hae->administracao->relatorios }}">
        @endif

        @if($hae->administracao->tipo_admin == 'integracao')
            <p>Ações interdisciplinares: {{ $hae->administracao->acoes_interdisciplinares }}</p>
            <input type="number" name="resultados[acoes_interdisciplinares][realizado]">
            <input type="hidden" name="resultados[acoes_interdisciplinares][previsto]" value="{{ $hae->administracao->acoes_interdisciplinares }}">
        @endif

        @if($hae->administracao->tipo_admin == 'formacao')
            <p>Formações previstas: {{ $hae->administracao->formacoes }}</p>
            <input type="number" name="resultados[formacoes][realizado]">
            <input type="hidden" name="resultados[formacoes][previsto]" value="{{ $hae->administracao->formacoes }}">
        @endif

        @if($hae->administracao->tipo_admin == 'comunicacao')
            <p>Materiais previstos: {{ $hae->administracao->materiais }}</p>
            <input type="number" name="resultados[materiais][realizado]">
            <input type="hidden" name="resultados[materiais][previsto]" value="{{ $hae->administracao->materiais }}">
        @endif

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->administracao->indicador }}">
    @endif

    <!-- ================= ESTUDOS ================= -->
    @if($hae->tipo == 'estudos')
        <h2>Estudos</h2>

        @if($hae->estudos->tipo_estudo == 'alunos')
            <p>Alunos previstos: {{ $hae->estudos->alunos }}</p>
            <input type="number" name="resultados[alunos][realizado]">
            <input type="hidden" name="resultados[alunos][previsto]" value="{{ $hae->estudos->alunos }}">
        @endif

        @if($hae->estudos->tipo_estudo == 'propostas')
            <p>Propostas previstas: {{ $hae->estudos->propostas }}</p>
            <input type="number" name="resultados[propostas][realizado]">
            <input type="hidden" name="resultados[propostas][previsto]" value="{{ $hae->estudos->propostas }}">
        @endif

        @if($hae->estudos->tipo_estudo == 'prototipos')
            <p>Protótipos previstos: {{ $hae->estudos->prototipos }}</p>
            <input type="number" name="resultados[prototipos][realizado]">
            <input type="hidden" name="resultados[prototipos][previsto]" value="{{ $hae->estudos->prototipos }}">
        @endif

        @if($hae->estudos->tipo_estudo == 'revista')
            <p>Artigos previstos: {{ $hae->estudos->artigos }}</p>
            <input type="number" name="resultados[artigos][realizado]">
            <input type="hidden" name="resultados[artigos][previsto]" value="{{ $hae->estudos->artigos }}">
        @endif

        @if($hae->estudos->tipo_estudo == 'resumos')
            <p>Resumos previstos: {{ $hae->estudos->resumos }}</p>
            <input type="number" name="resultados[resumos][realizado]">
            <input type="hidden" name="resultados[resumos][previsto]" value="{{ $hae->estudos->resumos }}">
        @endif

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->estudos->indicador }}">
    @endif

    <!-- ================= EXTENSÃO ================= -->
    @if($hae->tipo == 'extensao')
        <h2>Extensão</h2>

        @if($hae->extensao->tipo_extensao == 'pessoas')
            <p>Pessoas previstas: {{ $hae->extensao->pessoas }}</p>
            <input type="number" name="resultados[pessoas][realizado]">
            <input type="hidden" name="resultados[pessoas][previsto]" value="{{ $hae->extensao->pessoas }}">
        @endif

        @if($hae->extensao->tipo_extensao == 'instituicoes')
            <p>Instituições previstas: {{ $hae->extensao->instituicoes }}</p>
            <input type="number" name="resultados[instituicoes][realizado]">
            <input type="hidden" name="resultados[instituicoes][previsto]" value="{{ $hae->extensao->instituicoes }}">
        @endif

        @if($hae->extensao->tipo_extensao == 'eventos')
            <p>Eventos previstos: {{ $hae->extensao->eventos }}</p>
            <input type="number" name="resultados[eventos][realizado]">
            <input type="hidden" name="resultados[eventos][previsto]" value="{{ $hae->extensao->eventos }}">
        @endif

        @if($hae->extensao->tipo_extensao == 'beneficiarios')
            <p>Beneficiários previstos: {{ $hae->extensao->beneficiarios }}</p>
            <input type="number" name="resultados[beneficiarios][realizado]">
            <input type="hidden" name="resultados[beneficiarios][previsto]" value="{{ $hae->extensao->beneficiarios }}">
        @endif

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->extensao->indicador }}">
    @endif

    <!-- ================= PLANTÃO ================= -->
    @if($hae->tipo == 'plantao')
        <h2>Plantão</h2>

        @if($hae->plantao->tipo_plantao == 'alunos')
            <p>Alunos previstos: {{ $hae->plantao->alunos_atendidos }}</p>
            <input type="number" name="resultados[alunos_atendidos][realizado]">
            <input type="hidden" name="resultados[alunos_atendidos][previsto]" value="{{ $hae->plantao->alunos_atendidos }}">
        @endif

        @if($hae->plantao->tipo_plantao == 'simulados')
            <p>Simulados previstos: {{ $hae->plantao->simulados }}</p>
            <input type="number" name="resultados[simulados][realizado]">
            <input type="hidden" name="resultados[simulados][previsto]" value="{{ $hae->plantao->simulados }}">
        @endif

        @if($hae->plantao->tipo_plantao == 'relatorios')
            <p>Relatórios previstos: {{ $hae->plantao->relatorios }}</p>
            <input type="number" name="resultados[relatorios][realizado]">
            <input type="hidden" name="resultados[relatorios][previsto]" value="{{ $hae->plantao->relatorios }}">
        @endif

        @if($hae->plantao->tipo_plantao == 'acoes')
            <p>Ações previstas: {{ $hae->plantao->acoes }}</p>
            <input type="number" name="resultados[acoes][realizado]">
            <input type="hidden" name="resultados[acoes][previsto]" value="{{ $hae->plantao->acoes }}">
        @endif

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->plantao->indicador }}">
    @endif

    <!-- ================= AMS ================= -->
    @if($hae->tipo == 'ams')
        <h2>AMS</h2>

        @if($hae->ams->tipo_ams == 'escolas')
            <p>Escolas previstas: {{ $hae->ams->escolas }}</p>
            <input type="number" name="resultados[escolas][realizado]">
            <input type="hidden" name="resultados[escolas][previsto]" value="{{ $hae->ams->escolas }}">
        @endif

        @if($hae->ams->tipo_ams == 'eventos')
            <p>Eventos previstos: {{ $hae->ams->eventos }}</p>
            <input type="number" name="resultados[eventos][realizado]">
            <input type="hidden" name="resultados[eventos][previsto]" value="{{ $hae->ams->eventos }}">
        @endif

        @if($hae->ams->tipo_ams == 'encontros')
            <p>Encontros previstos: {{ $hae->ams->encontros_alunos }}</p>
            <input type="number" name="resultados[encontros_alunos][realizado]">
            <input type="hidden" name="resultados[encontros_alunos][previsto]" value="{{ $hae->ams->encontros_alunos }}">
        @endif

        <label>Indicador</label>
        <input type="text" name="indicador" value="{{ $hae->ams->indicador }}">
    @endif

    <hr>

    <h2>📎 Upload</h2>

    <input type="file" name="arquivo_principal">
    <input type="file" name="comprovacoes[]" multiple>

    <br><br>
    <button type="submit">Enviar</button>
</form>