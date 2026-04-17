@if($hae->ams)
    <p><strong>Tipo:</strong> {{ $hae->ams->tipo_ams }}</p>

    @if($hae->ams->escolas)
        <p><strong>Escolas visitadas:</strong> {{ $hae->ams->escolas }}</p>
    @endif

    @if($hae->ams->eventos)
        <p><strong>Eventos:</strong> {{ $hae->ams->eventos }}</p>
    @endif

    @if($hae->ams->encontros_alunos)
        <p><strong>Encontros com alunos:</strong> {{ $hae->ams->encontros_alunos }}</p>
    @endif

    <p><strong>Indicador:</strong> {{ $hae->ams->indicador }}</p>

    @if($hae->ams->outra_acao)
        <p><strong>Outra ação:</strong> {{ $hae->ams->outra_acao }}</p>
    @endif
@endif