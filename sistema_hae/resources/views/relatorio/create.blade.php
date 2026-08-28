@extends('layouts.app')

@section('title', 'Relatório da HAE')
@section('eyebrow', 'Conclusão da atividade')
@section('page-title', 'Enviar relatório')
@section('page-subtitle', $hae->titulo)

@section('header-actions')
    <a href="{{ route('hae.show', $hae->id) }}" class="button button--secondary">← Voltar para a HAE</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('relatorio.store', $hae->id) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        <div class="form-card__intro"><h2>Relatório de execução</h2><p>Registre o que foi realizado e anexe os documentos que comprovam a atividade.</p></div>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">1</span><h3>Informações gerais</h3></div>
            <div class="form-grid">
                <div class="field field--full"><label for="titulo">Título do relatório</label><input type="text" id="titulo" name="titulo" value="{{ old('titulo', $hae->relatorio->titulo ?? '') }}" required></div>
                <div class="field field--full"><label for="sumario">Sumário executivo</label><textarea id="sumario" name="sumario" required>{{ old('sumario', $hae->relatorio->sumario ?? '') }}</textarea><span class="field__hint">Apresente de forma resumida o desenvolvimento da atividade.</span></div>
                <div class="field field--full"><label for="resultados_texto">Principais resultados</label><textarea id="resultados_texto" name="resultados_texto" required>{{ old('resultados_texto', $hae->relatorio->resultados_texto ?? '') }}</textarea><span class="field__hint">Descreva entregas, impactos e resultados alcançados.</span></div>
            </div>
        </section>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">2</span><h3>Documentos</h3></div>
            <div class="form-grid">
                <div class="field"><label for="arquivo_principal">Arquivo principal</label><input type="file" id="arquivo_principal" name="arquivo_principal" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.odt,.xls,.xlsx"><span class="field__hint">PDF, imagem ou documento Office/OpenDocument, com até 10 MB.</span></div>
                <div class="field"><label for="comprovacoes">Comprovações</label><input type="file" id="comprovacoes" name="comprovacoes[]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.odt,.xls,.xlsx" multiple><span class="field__hint">Até 10 arquivos permitidos, com no máximo 10 MB cada.</span></div>
            </div>
        </section>

        <div class="form-actions"><a href="{{ route('hae.show', $hae->id) }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">Enviar relatório</button></div>
    </form>
@endsection
