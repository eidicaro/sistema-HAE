@extends('layouts.app')

@section('title', isset($hae) ? 'Editar HAE' : 'Nova HAE')
@section('eyebrow', 'Área do professor')
@section('page-title', isset($hae) ? 'Revisar e reenviar HAE' : 'Nova proposta HAE')
@section('page-subtitle', isset($hae) ? 'Faça os ajustes solicitados e envie novamente para análise.' : 'Preencha as informações da atividade com clareza e objetividade.')

@section('header-actions')
    <a href="{{ route('professor') }}" class="button button--secondary">← Voltar</a>
@endsection

@section('content')
    <form method="POST" action="{{ isset($hae) ? route('hae.update', $hae->id) : route('hae.store') }}" class="form-card">
        @csrf
        @isset($hae) @method('PUT') @endisset

        <div class="form-card__intro">
            <h2>{{ isset($hae) ? 'Atualização da proposta' : 'Dados da proposta' }}</h2>
            <p>Campos marcados como obrigatórios precisam ser preenchidos para concluir o envio.</p>
        </div>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">1</span><h3>Termo do edital</h3></div>
            <div class="checkbox-card">
                <input type="checkbox" id="edital" name="edital" value="1" {{ old('edital', $hae->edital_aceito ?? false) ? 'checked' : '' }} required>
                <label for="edital">Declaro que li e estou de acordo com o EDITAL DE HAE Nº 02/2025 – 1º SEMESTRE DE 2026.</label>
            </div>
            @error('edital')<span class="field__error">{{ $message }}</span>@enderror
        </section>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">2</span><h3>Identificação da atividade</h3></div>
            <div class="form-grid">
                <div class="field">
                    <label for="tipo_hae_id">Tipo de HAE</label>
                    <select id="tipo_hae_id" name="tipo_hae_id" required>
                        <option value="">Selecione um tipo</option>
                        @foreach($nomes as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('tipo_hae_id', $hae->tipo_hae_id ?? '') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                    @error('tipo_hae_id')<span class="field__error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="curso">Curso com maior aderência</label>
                    <select id="curso" name="curso" required>
                        <option value="">Selecione um curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso }}" {{ old('curso', $hae->curso ?? '') == $curso ? 'selected' : '' }}>{{ $curso }}</option>
                        @endforeach
                    </select>
                    @error('curso')<span class="field__error">{{ $message }}</span>@enderror
                </div>
                <div class="field field--full">
                    <label for="titulo">Título do projeto</label>
                    <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $hae->titulo ?? '') }}" maxlength="255" required>
                    @error('titulo')<span class="field__error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="carga_horaria">Carga horária semanal solicitada</label>
                    <input type="number" id="carga_horaria" name="carga_horaria" value="{{ old('carga_horaria', $hae->carga_horaria ?? '') }}" min="1" required>
                    <span class="field__hint">Informe apenas horas inteiras.</span>
                    @error('carga_horaria')<span class="field__error">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">3</span><h3>Descrição da proposta</h3></div>
            <div class="form-grid">
                <div class="field field--full"><label for="resumo">Resumo do projeto</label><textarea id="resumo" name="resumo" required>{{ old('resumo', $hae->resumo ?? '') }}</textarea>@error('resumo')<span class="field__error">{{ $message }}</span>@enderror</div>
                <div class="field field--full"><label for="justificativa">Justificativa</label><textarea id="justificativa" name="justificativa" required>{{ old('justificativa', $hae->justificativa ?? '') }}</textarea>@error('justificativa')<span class="field__error">{{ $message }}</span>@enderror</div>
                <div class="field field--full"><label for="especificacoes">Especificações da HAE</label><textarea id="especificacoes" name="especificacoes" placeholder="Detalhes, entregas ou características relevantes da atividade.">{{ old('especificacoes', $hae->especificacoes ?? '') }}</textarea><span class="field__hint">Campo opcional.</span></div>
            </div>
        </section>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">4</span><h3>Cronograma</h3></div>
            <div class="field"><label for="cronograma">Dias e horários das atividades</label><textarea id="cronograma" name="cronograma" placeholder="Exemplo: segundas e quartas, das 19h às 21h">{{ old('cronograma', $hae->cronograma ?? '') }}</textarea><span class="field__hint">Organize as informações por dia, horário ou etapa.</span></div>
        </section>

        <div class="form-actions"><a href="{{ route('professor') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">{{ isset($hae) ? 'Salvar e reenviar' : 'Enviar proposta' }}</button></div>
    </form>
@endsection
