<div class="form-grid">
    <div class="field field--full">
        <label for="nome">Nome do tipo pai</label>
        <input type="text" name="nome" id="nome" value="{{ old('nome', $tipoHae->nome ?? '') }}" maxlength="255" required>
        @error('nome')<span class="field__error">{{ $message }}</span>@enderror
    </div>
    <div class="field field--full">
        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" rows="4" placeholder="Explique a finalidade deste tipo de HAE.">{{ old('descricao', $tipoHae->descricao ?? '') }}</textarea>
        @error('descricao')<span class="field__error">{{ $message }}</span>@enderror
    </div>
    <div class="field">
        <label for="limite">Limite compartilhado de horas</label>
        <input type="number" name="limite" id="limite" min="0" value="{{ old('limite', $tipoHae->limite ?? 0) }}" required>
        <span class="field__hint">Capacidade total por semestre, consumida pela soma de todos os subtipos.</span>
        @error('limite')<span class="field__error">{{ $message }}</span>@enderror
    </div>
    <div class="field field--full">
        <div class="checkbox-card">
            <input type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', $tipoHae->ativo ?? true) ? 'checked' : '' }}>
            <label for="ativo">Disponibilizar este tipo para novas submissões</label>
        </div>
    </div>
</div>
