<div class="th-field">
    <label for="nome">Nome</label>
    <input type="text" name="nome" id="nome" value="{{ old('nome', $tipoHae->nome ?? '') }}" required>
    @error('nome') <span class="th-error">{{ $message }}</span> @enderror
</div>

<div class="th-field">
    <label for="descricao">Descrição</label>
    <textarea name="descricao" id="descricao" rows="3">{{ old('descricao', $tipoHae->descricao ?? '') }}</textarea>
    @error('descricao') <span class="th-error">{{ $message }}</span> @enderror
</div>

<div class="th-field">
    <label for="limite">Limite (horas)</label>
    <input type="number" name="limite" id="limite" min="0" value="{{ old('limite', $tipoHae->limite ?? 0) }}" required>
    @error('limite') <span class="th-error">{{ $message }}</span> @enderror
</div>

<div class="th-field th-checkbox">
    <label>
        <input type="checkbox" name="ativo" value="1" {{ old('ativo', $tipoHae->ativo ?? true) ? 'checked' : '' }}>
        Ativo
    </label>
</div>