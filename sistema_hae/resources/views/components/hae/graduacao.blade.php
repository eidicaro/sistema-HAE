@if($hae->graduacao)
    <p><strong>Tipo:</strong> {{ $hae->graduacao->tipo_graduacao }}</p>
    <p><strong>Orientações:</strong> {{ $hae->graduacao->orientacoes }}</p>
    <p><strong>Bancas (Orientador):</strong> {{ $hae->graduacao->bancas_orientador }}</p>
    <p><strong>Bancas (Membro):</strong> {{ $hae->graduacao->bancas_membro }}</p>
    <p><strong>Indicador:</strong> {{ $hae->graduacao->indicador }}</p>
@endif