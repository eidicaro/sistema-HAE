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

        @if($nomes->isEmpty())
            <div class="alert alert--error" role="alert"><span class="alert__dot"></span><div><strong>Submissão indisponível</strong><p>A direção ainda precisa cadastrar ao menos um tipo de HAE ativo.</p></div></div>
        @endif

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">1</span><h3>Sobre o EDITAL DE HAE Nº 01/2026 – 2º SEMESTRE DE 2026</h3></div>
            <div class="field">
                <label for="edital">Sobre o EDITAL DE HAE Nº 01/2026 – 2º SEMESTRE DE 2026</label>
                <select id="edital" name="edital" required>
                    <option value="">Selecione uma opção</option>
                    <option value="1" {{ old('edital', isset($hae) && $hae->edital_aceito ? '1' : '') === '1' ? 'selected' : '' }}>Li e estou de acordo</option>
                    <option value="0" {{ old('edital', isset($hae) && ! $hae->edital_aceito ? '0' : '') === '0' ? 'selected' : '' }}>Não desejo submeter</option>
                </select>
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
                    <label for="subtipo_hae_id">Subtipo de HAE</label>
                    <select id="subtipo_hae_id" name="subtipo_hae_id">
                        <option value="">Selecione primeiro o tipo</option>
                        @foreach($nomes as $tipo)
                            <optgroup label="{{ $tipo->nome }}">
                                @foreach($tipo->subtipos as $subtipo)
                                    <option value="{{ $subtipo->id }}" data-tipo-hae="{{ $tipo->id }}" {{ old('subtipo_hae_id', $hae->subtipo_hae_id ?? '') == $subtipo->id ? 'selected' : '' }}>{{ $subtipo->nome }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <span class="field__hint">Obrigatório somente quando o tipo selecionado possuir subtipos ativos.</span>
                    @error('subtipo_hae_id')<span class="field__error">{{ $message }}</span>@enderror
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
                <div class="field field--full"><label for="justificativa">Justificativa do projeto</label><textarea id="justificativa" name="justificativa" required>{{ old('justificativa', $hae->justificativa ?? '') }}</textarea>@error('justificativa')<span class="field__error">{{ $message }}</span>@enderror</div>
                <div class="field field--full"><label for="resultados_esperados">Resultados esperados</label><textarea id="resultados_esperados" name="resultados_esperados" placeholder="Descreva os resultados esperados da HAE.">{{ old('resultados_esperados', $hae->resultados_esperados ?? '') }}</textarea>@error('resultados_esperados')<span class="field__error">{{ $message }}</span>@enderror</div>
                <div class="field field--full"><label for="indicadores">Indicadores</label><textarea id="indicadores" name="indicadores" placeholder="Informe os indicadores que serão utilizados para avaliar os resultados.">{{ old('indicadores', $hae->indicadores ?? '') }}</textarea>@error('indicadores')<span class="field__error">{{ $message }}</span>@enderror</div>
            </div>
        </section>

        <section class="form-section">
            <div class="form-section__title"><span class="form-section__number">4</span><h3>Cronograma</h3></div>
            <div class="table-wrap schedule-table">
                <table class="data-table">
                    <thead><tr><th>Mês</th><th>Desenvolvimento previsto</th></tr></thead>
                    <tbody>
                        @for($i = 1; $i <= 5; $i++)
                            <tr>
                                <td><strong>Mês {{ $i }}</strong></td>
                                <td>
                                    <div class="field">
                                        <label for="mes_{{ $i }}" class="sr-only">Desenvolvimento previsto para o mês {{ $i }}</label>
                                        <input type="text" id="mes_{{ $i }}" name="mes_{{ $i }}" value="{{ old('mes_'.$i, isset($hae) ? $hae->{'mes_'.$i} : '') }}" placeholder="Descreva as atividades previstas para este mês">
                                        @error("mes_{$i}")<span class="field__error">{{ $message }}</span>@enderror
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="field schedule-hours"><label for="horarios_hae">Horários da HAE</label><textarea id="horarios_hae" name="horarios_hae" placeholder="Ex.: Segundas e Quartas das 19h às 21h">{{ old('horarios_hae', $hae->horarios_hae ?? '') }}</textarea>@error('horarios_hae')<span class="field__error">{{ $message }}</span>@enderror</div>
        </section>

        <div class="form-actions"><a href="{{ route('professor') }}" class="button button--secondary">Cancelar</a><button type="submit" class="button">{{ isset($hae) ? 'Salvar e reenviar' : 'Enviar proposta' }}</button></div>
    </form>
@endsection
