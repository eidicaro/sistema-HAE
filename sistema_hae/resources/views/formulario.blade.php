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

<form method="POST" action="/salvar-hae">
@csrf

<!-- ================= FIXO ================= -->

<h2>Sobre o EDITAL DE HAE Nº 02/2025 – 1º SEMESTRE DE 2026</h2>

<label>Sobre o EDITAL DE HAE Nº 02/2025 – 1º SEMESTRE DE 2026</label>
<select name="edital">
    <option value="1">Li e estou de acordo</option>
    <option value="0">Não desejo submeter</option>
</select>

<h2>Das Informações Iniciais</h2>

<label>Nome do Professor</label>
<input type="text" name="nome">

<label>Curso com maior aderência ao projeto</label>
<select name="curso">
    <option>Automação Industrial</option>
    <option>Manutenção Industrial</option>
    <option>Gestão Empresarial</option>
    <option>Gestão da Tecnologia da Informação</option>
    <option>Produção Fonográfica</option>
    <option>AMS - Análise e Desenvolvimento de Sistemas</option>
    <option>AMS - Processos Gerenciais</option>
</select>

<label>Titulo do Projeto</label>
<input type="text" name="titulo">

<label>Carga Horaria Semanal Solicitada</label>
<input type="number" name="carga_horaria">

<label>Resumo do Projeto</label>
<textarea name="resumo"></textarea>

<label>Justificativa do Projeto</label>
<textarea name="justificativa"></textarea>

<h2>Do Cronograma</h2>

<input type="date" name="fevereiro" placeholder="Fevereiro">
<input type="date" name="marco" placeholder="Março">
<input type="date" name="abril" placeholder="Abril">
<input type="date" name="maio" placeholder="Maio">
<input type="date" name="junho" placeholder="Junho">

<input type="hidden" name="tipo" value="{{ $tipo }}">


    <!-- ================= PROJETO DE GRADUAÇÃO ================= -->
    @if($tipo == 'graduacao')
        <h2>Projeto de Graduação</h2>

        <label>Projeto de Graduação</label>
        <select name="tipo_graduacao">
            <option>Orientação de Trabalho de Graduação</option>
            <option>Outra</option>
        </select>

        <label>Número de Orientações de PTG e TG Previstas:</label>
        <input type="number" name="orientacoes">

        <label>Número de Participações em Bancas Previstas como Orientador:</label>
        <input type="number" name="bancas_orientador">

        <label>Número de Participações em Bancas Previstas como Membro:</label>
        <input type="number" name="bancas_membro">

        <label>Especificar Indicador de Desempenho:</label>
        <input type="number" name="indicador">
    @endif

<!-- ================= ADMINISTRAÇÃO ================= -->
@if($tipo == 'administracao')
<div id="administracao">

<h2>Administração Acadêmica</h2>

<label>Administração Acadêmica</label>
<select id="tipo_admin" name="tipo_admin">
    <option value="">Selecione</option>
    <option value="avaliacao">Apoio à Avaliação Institucional (CPA e RAAI)</option>
    <option value="indicadores">Planejamento e Gestão de Indicadores de Permanência e Êxito Discente</option>
    <option value="integracao">Promoção da Integração Curricular e Interdisciplinaridade</option>
    <option value="formacao">Apoio à Formação Docente Contínua</option>
    <option value="comunicacao">Gestão da Comunicação Acadêmica Institucional</option>
</select>

<div id="admin-avaliacao" class="sub-bloco">
    <label>Previsão do Número de ações de encontros previstos com a comissão (CPA e RAAI):</label>
    <input type="number" name="encontros">
</div>

<div id="admin-indicadores" class="sub-bloco">
    <label>Previsão de Número de relatórios preenchidos, entregues ou atualizados sobre permanência e êxito:</label>
    <input type="number" name="relatorios">
</div>

<div id="admin-integracao" class="sub-bloco">
    <label>Previsão de Número de ações interdisciplinares implementadas:</label>
    <input type="number" name="acoes_interdisciplinares">
</div>

<div id="admin-formacao" class="sub-bloco">
    <label>Previsão do Número de formações, oficinas ou reuniões pedagógicas organizadas:</label>
    <input type="number" name="formacoes">
</div>

<div id="admin-comunicacao" class="sub-bloco">
    <label>Previsão do Número de materiais e canais informativos produzidos:</label>
    <input type="number" name="materiais">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao">

</div>
@endif

<!-- ================= ESTUDOS ================= -->
@if($tipo == 'estudos')
<div id="estudos">

<h2>Estudos e Projetos</h2>

<select id="tipo_estudo" name="tipo_estudo">
    <option value="">Selecione</option>
    <option value="alunos">Pesquisa com Discentes</option>
    <option value="propostas">Propostas a Editais</option>
    <option value="prototipos">Projetos de Inovação</option>
    <option value="revista">Apoio a Revista Sapere</option>
    <option value="resumos">Apoio a elaboração de caderno de resumos</option>
</select>

<div id="estudos-alunos" class="sub-bloco">
    <label>Previsão do número de alunos envolvidos em pesquisas discentes:</label>
    <input type="number" name="alunos">
</div>

<div id="estudos-propostas" class="sub-bloco">
    <label>Previsão do número de propostas submetidas a editais:</label>
    <input type="number" name="propostas">
</div>

<div id="estudos-prototipos" class="sub-bloco">
    <label>Previsão do número de protótipos, soluções, relatórios ou parcerias inovadoras geradas:</label>
    <input type="number" name="prototipos">
</div>

<div id="estudos-revista" class="sub-bloco">
    <label>Previsão do número de edições ou artigos apoiados na Revista Sapere:</label>
    <input type="number" name="artigos">
</div>

<div id="estudos-resumos" class="sub-bloco">
    <label>Previsão do número de trabalhos organizados em cadernos de resumo:</label>
    <input type="number" name="resumos">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao">

</div>
@endif

<!-- ================= EXTENSÃO ================= -->
@if($tipo == 'extensao')
<div id="extensao">

<h2>Extensão de Serviços à Comunidade</h2>

<select id="tipo_extensao" name="tipo_extensao">
    <option value="">Selecione</option>
    <option value="pessoas">Oficinas ou Cursos Livres</option>
    <option value="instituicoes">Parcerias com Órgãos Publicos</option>
    <option value="eventos">Eventos Institucionais</option>
    <option value="beneficiarios">Projetos de Impacto Social</option>
</select>

<div id="extensao-pessoas" class="sub-bloco">
    <label>Previsão de número de pessoas atingidas:</label>
    <input type="number" name="pessoas">
</div>

<div id="extensao-instituicoes" class="sub-bloco">
    <label>Previsão de número de instituições públicas parceiras:</label>
    <input type="number" name="instituicoes">
</div>

<div id="extensao-eventos" class="sub-bloco">
    <label>Previsão de número de eventos institucionais realizados:</label>
    <input type="number" name="eventos">
</div>

<div id="extensao-beneficiarios" class="sub-bloco">
    <label>Previsão de número de beneficiários e produtos gerados em projetos sociais:</label>
    <input type="number" name="beneficiarios">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao">

</div>
@endif

<!-- ================= PLANTÃO ================= -->
@if($tipo == 'plantao')
<div id="plantao">

<h2>Plantão Didático</h2>

<select id="tipo_plantao" name="tipo_plantao">
    <option value="">Selecione</option>
    <option value="alunos">Atendimento Individualizado</option>
    <option value="simulados">Simulados e Materiais de Apoio</option>
    <option value="relatorios">Acompanhamento Pedagógico</option>
    <option value="acoes">Ações de Permanência</option>
</select>

<div id="plantao-alunos" class="sub-bloco">
    <label>Previsão de Número de alunos atendidos individualmente:</label>
    <input type="number" name="alunos_atendidos">
</div>

<div id="plantao-simulados" class="sub-bloco">
    <label>Previsão da Quantidade de simulados e materiais aplicados:</label>
    <input type="number" name="simulados">
</div>

<div id="plantao-relatorios" class="sub-bloco">
    <label>Previsão de Número de devolutivas ou relatórios pedagógicos realizados:</label>
    <input type="number" name="relatorios">
</div>

<div id="plantao-acoes" class="sub-bloco">
    <label>Previsão de Número de ações de permanência realizadas (grupos, mentorias etc.):</label>
    <input type="number" name="acoes">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao">

</div>
@endif

<!-- ================= AMS ================= -->
@if($tipo == 'ams')
<div id="ams">

<h2>PROGRAMA AMS</h2>

<select id="tipo_ams" name="tipo_ams">
    <option value="">Selecione</option>
    <option value="escolas">visitas em Escolas Públicas</option>
    <option value="eventos">Participação da Rota da Inovação</option>
    <option value="encontros">Acolhimento e Acompanhamento de Turmas AMS</option>
</select>

<div id="ams-escolas" class="sub-bloco">
    <label>Previsão de Número de Escolas Públicas a Serem Visitadas:</label>
    <input type="number" name="escolas">
</div>

<div id="ams-eventos" class="sub-bloco">
    <label>Previsão de Número de Eventos/Atividades de Inovação Previstas (Rota da Inovação):</label>
    <input type="number" name="eventos">
</div>

<div id="ams-encontros" class="sub-bloco">
    <label>Previsão de Número de Encontros com Alunos dos 1º, 2º e 3º anos:</label>
    <input type="number" name="encontros_alunos">
</div>

<label>Especificar Indicador de Desempenho:</label>
<input type="text" name="indicador">

<label>Outra ação prevista (especificar):</label>
<input type="text" name="outra_acao">

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

<!-- ================= JS ================= -->
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

// Ativa conforme tipo
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