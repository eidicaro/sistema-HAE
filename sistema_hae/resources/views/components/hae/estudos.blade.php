@if($hae->estudos)
    <p><strong>Tipo:</strong> {{ $hae->estudos->tipo_estudo }}</p>

    @if($hae->estudos->alunos)
        <p><strong>Alunos envolvidos:</strong> {{ $hae->estudos->alunos }}</p>
    @endif

    @if($hae->estudos->propostas)
        <p><strong>Propostas:</strong> {{ $hae->estudos->propostas }}</p>
    @endif

    @if($hae->estudos->prototipos)
        <p><strong>Protótipos:</strong> {{ $hae->estudos->prototipos }}</p>
    @endif

    @if($hae->estudos->artigos)
        <p><strong>Artigos:</strong> {{ $hae->estudos->artigos }}</p>
    @endif

    @if($hae->estudos->resumos)
        <p><strong>Resumos:</strong> {{ $hae->estudos->resumos }}</p>
    @endif

    <p><strong>Indicador:</strong> {{ $hae->estudos->indicador }}</p>

    @if($hae->estudos->outra_acao)
        <p><strong>Outra ação:</strong> {{ $hae->estudos->outra_acao }}</p>
    @endif
@endif