@if($hae->plantao)
    <p><strong>Tipo:</strong> {{ $hae->plantao->tipo_plantao }}</p>

    @if($hae->plantao->alunos_atendidos)
        <p><strong>Alunos atendidos:</strong> {{ $hae->plantao->alunos_atendidos }}</p>
    @endif

    @if($hae->plantao->simulados)
        <p><strong>Simulados:</strong> {{ $hae->plantao->simulados }}</p>
    @endif

    @if($hae->plantao->relatorios)
        <p><strong>Relatórios:</strong> {{ $hae->plantao->relatorios }}</p>
    @endif

    @if($hae->plantao->acoes)
        <p><strong>Ações:</strong> {{ $hae->plantao->acoes }}</p>
    @endif

    <p><strong>Indicador:</strong> {{ $hae->plantao->indicador }}</p>

    @if($hae->plantao->outra_acao)
        <p><strong>Outra ação:</strong> {{ $hae->plantao->outra_acao }}</p>
    @endif
@endif