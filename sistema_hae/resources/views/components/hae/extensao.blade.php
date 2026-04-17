@if($hae->extensao)
    <p><strong>Tipo:</strong> {{ $hae->extensao->tipo_extensao }}</p>

    @if($hae->extensao->pessoas)
        <p><strong>Pessoas atingidas:</strong> {{ $hae->extensao->pessoas }}</p>
    @endif

    @if($hae->extensao->instituicoes)
        <p><strong>Instituições:</strong> {{ $hae->extensao->instituicoes }}</p>
    @endif

    @if($hae->extensao->eventos)
        <p><strong>Eventos:</strong> {{ $hae->extensao->eventos }}</p>
    @endif

    @if($hae->extensao->beneficiarios)
        <p><strong>Beneficiários:</strong> {{ $hae->extensao->beneficiarios }}</p>
    @endif

    <p><strong>Indicador:</strong> {{ $hae->extensao->indicador }}</p>

    @if($hae->extensao->outra_acao)
        <p><strong>Outra ação:</strong> {{ $hae->extensao->outra_acao }}</p>
    @endif
@endif