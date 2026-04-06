<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nova HAE</title>
    <link rel="stylesheet" href="{{ asset('../css/formulario.css') }}">
</head>
<body>

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

<label>Nome do Professor</label>
<input type="text" name="nome" value="{{ old('nome') }}">

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

<h2>Do Cronograma</h2>

<input type="date" name="fevereiro" value="{{ old('fevereiro', $hae->fevereiro ?? '') }}" placeholder="Fevereiro">
<input type="date" name="marco" value="{{ old('marco', $hae->marco ?? '') }}" placeholder="Março">
<input type="date" name="abril" value="{{ old('abril', $hae->abril ?? '') }}" placeholder="Abril">
<input type="date" name="maio" value="{{ old('maio', $hae->maio ?? '') }}" placeholder="Maio">
<input type="date" name="junho" value="{{ old('junho', $hae->junho ?? '') }}" placeholder="Junho">

<input type="hidden" name="tipo" value="{{ $tipo }}">


    <!-- ================= PROJETO DE GRADUAÇÃO ================= -->
    @if($tipo == 'graduacao')
        <h2>Projeto de Graduação</h2>

        <label>Projeto de Graduação</label>
        <select name="tipo_graduacao">
            <option {{ old('tipo_graduacao') == 'Orientação de Trabalho de Graduação' ? 'selected' : '' }}>Orientação de Trabalho de Graduação</option>
            <option {{ old('tipo_graduacao') == 'Outra' ? 'selected' : '' }}>Outra</option>
        </select>

        <label>Número de Orientações de PTG e TG Previstas:</label>
        <input type="number" name="orientacoes" value="{{ old('orientacoes') }}">

        <label>Número de Participações em Bancas Previstas como Orientador:</label>
        <input type="number" name="bancas_orientador" value="{{ old('bancas_orientador') }}">

        <label>Número de Participações em Bancas Previstas como Membro:</label>
        <input type="number" name="bancas_membro" value="{{ old('bancas_membro') }}">

        <label>Especificar Indicador de Desempenho:</label>
        <input type="number" name="indicador" value="{{ old('indicador') }}">
    @endif

<!-- ================= ADMINISTRAÇÃO ================= -->
@if($tipo == 'administracao')
<div id="administracao">

<h2>Administração Acadêmica</h2>

<label>Administração Acadêmica</label>
<select id="tipo_admin" name="tipo_admin">
    <option value="">Selecione</option>
    <option value="avaliacao" {{ old('tipo_admin') == 'avaliacao' ? 'selected' : '' }}>Apoio à Avaliação Institucional (CPA e RAAI)</option>
    <option value="indicadores" {{ old('tipo_admin') == 'indicadores' ? 'selected' : '' }}>Planejamento e Gestão de Indicadores de Permanência e Êxito Discente</option>
    <option value="integracao" {{ old('tipo_admin') == 'integracao' ? 'selected' : '' }}>Promoção da Integração Curricular e Interdisciplinaridade</option>
    <option value="formacao" {{ old('tipo_admin') == 'formacao' ? 'selected' : '' }}>Apoio à Formação Docente Contínua</option>
    <option value="comunicacao" {{ old('tipo_admin') == 'comunicacao' ? 'selected' : '' }}>Gestão da Comunicação Acadêmica Institucional</option>
</select>

<div id="admin-avaliacao" class="sub-bloco">
    <label>Previsão do Número de ações de encontros previstos com a comissão (CPA e RAAI):</label>
    <input type="number" name="encontros" value="{{ old('encontros') }}">
</div>

<div id="admin-indicadores" class="sub-bloco">
    <label>Previsão de Número de relatórios preenchidos, entregues ou atualizados sobre permanência e êxito:</label>
    <input type="number" name="relatorios" value="{{ old('relatorios') }}">
</div>

<div id="admin-integracao" class="sub-bloco">
    <label>Previsão de Número de ações interdisciplinares implementadas:</label>
    <input type="number" name="acoes_interdisciplinares" value="{{ old('acoes_interdisciplinares') }}">
</div>

<div id="admin-formacao" class="sub-bloco">
    <label>Previsão do Número de formações, oficinas ou reuniões pedagógicas organizadas:</label>
    <input type="number" name="formacoes" value="{{ old('formacoes') }}">
</div>

<div id="admin-comunicacao" class="sub-bloco">
    <label>Previsão do Número de materiais e canais informativos produzidos:</label>
    <input type="number" name="materiais" value="{{ old('materiais') }}">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador" value="{{ old('indicador') }}">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao" value="{{ old('outra_acao') }}">

</div>
@endif

<!-- ================= ESTUDOS ================= -->
@if($tipo == 'estudos')
<div id="estudos">

<h2>Estudos e Projetos</h2>

<select id="tipo_estudo" name="tipo_estudo">
    <option value="">Selecione</option>
    <option value="alunos" {{ old('tipo_estudo') == 'alunos' ? 'selected' : '' }}>Pesquisa com Discentes</option>
    <option value="propostas" {{ old('tipo_estudo') == 'propostas' ? 'selected' : '' }}>Propostas a Editais</option>
    <option value="prototipos" {{ old('tipo_estudo') == 'prototipos' ? 'selected' : '' }}>Projetos de Inovação</option>
    <option value="revista" {{ old('tipo_estudo') == 'revista' ? 'selected' : '' }}>Apoio a Revista Sapere</option>
    <option value="resumos" {{ old('tipo_estudo') == 'resumos' ? 'selected' : '' }}>Apoio a elaboração de caderno de resumos</option>
</select>

<div id="estudos-alunos" class="sub-bloco">
    <label>Previsão do número de alunos envolvidos em pesquisas discentes:</label>
    <input type="number" name="alunos" value="{{ old('alunos') }}">
</div>

<div id="estudos-propostas" class="sub-bloco">
    <label>Previsão do número de propostas submetidas a editais:</label>
    <input type="number" name="propostas" value="{{ old('propostas') }}">
</div>

<div id="estudos-prototipos" class="sub-bloco">
    <label>Previsão do número de protótipos, soluções, relatórios ou parcerias inovadoras geradas:</label>
    <input type="number" name="prototipos" value="{{ old('prototipos') }}">
</div>

<div id="estudos-revista" class="sub-bloco">
    <label>Previsão do número de edições ou artigos apoiados na Revista Sapere:</label>
    <input type="number" name="artigos" value="{{ old('artigos') }}">
</div>

<div id="estudos-resumos" class="sub-bloco">
    <label>Previsão do número de trabalhos organizados em cadernos de resumo:</label>
    <input type="number" name="resumos" value="{{ old('resumos') }}">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador" value="{{ old('indicador') }}">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao" value="{{ old('outra_acao') }}">

</div>
@endif

<!-- ================= EXTENSÃO ================= -->
@if($tipo == 'extensao')
<div id="extensao">

<h2>Extensão de Serviços à Comunidade</h2>

<select id="tipo_extensao" name="tipo_extensao">
    <option value="">Selecione</option>
    <option value="pessoas" {{ old('tipo_extensao') == 'pessoas' ? 'selected' : '' }}>Oficinas ou Cursos Livres</option>
    <option value="instituicoes" {{ old('tipo_extensao') == 'instituicoes' ? 'selected' : '' }}>Parcerias com Órgãos Publicos</option>
    <option value="eventos" {{ old('tipo_extensao') == 'eventos' ? 'selected' : '' }}>Eventos Institucionais</option>
    <option value="beneficiarios" {{ old('tipo_extensao') == 'beneficiarios' ? 'selected' : '' }}>Projetos de Impacto Social</option>
</select>

<div id="extensao-pessoas" class="sub-bloco">
    <label>Previsão de número de pessoas atingidas:</label>
    <input type="number" name="pessoas" value="{{ old('pessoas') }}">
</div>

<div id="extensao-instituicoes" class="sub-bloco">
    <label>Previsão de número de instituições públicas parceiras:</label>
    <input type="number" name="instituicoes" value="{{ old('instituicoes') }}">
</div>

<div id="extensao-eventos" class="sub-bloco">
    <label>Previsão de número de eventos institucionais realizados:</label>
    <input type="number" name="eventos" value="{{ old('eventos') }}">
</div>

<div id="extensao-beneficiarios" class="sub-bloco">
    <label>Previsão de número de beneficiários e produtos gerados em projetos sociais:</label>
    <input type="number" name="beneficiarios" value="{{ old('beneficiarios') }}">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador" value="{{ old('indicador') }}">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao" value="{{ old('outra_acao') }}">

</div>
@endif

<!-- ================= PLANTÃO ================= -->
@if($tipo == 'plantao')
<div id="plantao">

<h2>Plantão Didático</h2>

<select id="tipo_plantao" name="tipo_plantao">
    <option value="">Selecione</option>
    <option value="alunos" {{ old('tipo_plantao') == 'alunos' ? 'selected' : '' }}>Atendimento Individualizado</option>
    <option value="simulados" {{ old('tipo_plantao') == 'simulados' ? 'selected' : '' }}>Simulados e Materiais de Apoio</option>
    <option value="relatorios" {{ old('tipo_plantao') == 'relatorios' ? 'selected' : '' }}>Acompanhamento Pedagógico</option>
    <option value="acoes" {{ old('tipo_plantao') == 'acoes' ? 'selected' : '' }}>Ações de Permanência</option>
</select>

<div id="plantao-alunos" class="sub-bloco">
    <label>Previsão de Número de alunos atendidos individualmente:</label>
    <input type="number" name="alunos_atendidos" value="{{ old('alunos_atendidos') }}">
</div>

<div id="plantao-simulados" class="sub-bloco">
    <label>Previsão da Quantidade de simulados e materiais aplicados:</label>
    <input type="number" name="simulados" value="{{ old('simulados') }}">
</div>

<div id="plantao-relatorios" class="sub-bloco">
    <label>Previsão de Número de devolutivas ou relatórios pedagógicos realizados:</label>
    <input type="number" name="relatorios" value="{{ old('relatorios') }}">
</div>

<div id="plantao-acoes" class="sub-bloco">
    <label>Previsão de Número de ações de permanência realizadas (grupos, mentorias etc.):</label>
    <input type="number" name="acoes" value="{{ old('acoes') }}">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador" value="{{ old('indicador') }}">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao" value="{{ old('outra_acao') }}">

</div>
@endif

<!-- ================= AMS ================= -->
@if($tipo == 'ams')
<div id="ams">

<h2>PROGRAMA AMS</h2>

<select id="tipo_ams" name="tipo_ams">
    <option value="">Selecione</option>
    <option value="escolas" {{ old('tipo_ams') == 'escolas' ? 'selected' : '' }}>visitas em Escolas Públicas</option>
    <option value="eventos" {{ old('tipo_ams') == 'eventos' ? 'selected' : '' }}>Participação da Rota da Inovação</option>
    <option value="encontros" {{ old('tipo_ams') == 'encontros' ? 'selected' : '' }}>Acolhimento e Acompanhamento de Turmas AMS</option>
</select>

<div id="ams-escolas" class="sub-bloco">
    <label>Previsão de Número de Escolas Públicas a Serem Visitadas:</label>
    <input type="number" name="escolas" value="{{ old('escolas') }}">
</div>

<div id="ams-eventos" class="sub-bloco">
    <label>Previsão de Número de Eventos/Atividades de Inovação Previstas (Rota da Inovação):</label>
    <input type="number" name="eventos" value="{{ old('eventos') }}">
</div>

<div id="ams-encontros" class="sub-bloco">
    <label>Previsão de Número de Encontros com Alunos dos 1º, 2º e 3º anos:</label>
    <input type="number" name="encontros_alunos" value="{{ old('encontros_alunos') }}">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador" value="{{ old('indicador') }}">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao" value="{{ old('outra_acao') }}">

</div>
@endif

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

<script>
function ativarSubcategoria(selectId, prefixo) {
    document.getElementById(selectId).addEventListener("change", function () {
        let valor = this.value;

        document.querySelectorAll(`#${prefixo} .sub-bloco`).forEach(div => {
            div.style.display = "none";
        });

        if (valor) {
            let alvo = document.getElementById(prefixo + "-" + valor);
            if (alvo) alvo.style.display = "block";
        }
    });
}

@if($tipo == 'administracao')
    ativarSubcategoria("tipo_admin", "admin");
@endif

@if($tipo == 'estudos')
    ativarSubcategoria("tipo_estudo", "estudos");
@endif

@if($tipo == 'extensao')
    ativarSubcategoria("tipo_extensao", "extensao");
@endif

@if($tipo == 'plantao')
    ativarSubcategoria("tipo_plantao", "plantao");
@endif

@if($tipo == 'ams')
    ativarSubcategoria("tipo_ams", "ams");
@endif

</script>

</body>
</html>