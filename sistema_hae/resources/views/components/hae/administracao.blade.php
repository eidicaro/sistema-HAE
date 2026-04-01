@if($hae->administracao)
    <p><strong>Tipo:</strong> {{ $hae->administracao->tipo_admin }}</p>

    @if($hae->administracao->encontros)
        <p><strong>Encontros:</strong> {{ $hae->administracao->encontros }}</p>
    @endif

    @if($hae->administracao->relatorios)
        <p><strong>Relatórios:</strong> {{ $hae->administracao->relatorios }}</p>
    @endif

    @if($hae->administracao->acoes_interdisciplinares)
        <p><strong>Ações Interdisciplinares:</strong> {{ $hae->administracao->acoes_interdisciplinares }}</p>
    @endif

    @if($hae->administracao->formacoes)
        <p><strong>Formações:</strong> {{ $hae->administracao->formacoes }}</p>
    @endif

    @if($hae->administracao->materiais)
        <p><strong>Materiais:</strong> {{ $hae->administracao->materiais }}</p>
    @endif

    <p><strong>Indicador:</strong> {{ $hae->administracao->indicador }}</p>

    @if($hae->administracao->outra_acao)
        <p><strong>Outra ação:</strong> {{ $hae->administracao->outra_acao }}</p>
    @endif
@endif