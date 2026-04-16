<div class="bloco">
    <h2>Comparação de Resultados</h2>

    @if(!$relatorio || !$relatorio->relationLoaded('resultados') || $relatorio->resultados->isEmpty())
        <p class="vazio">Nenhum dado de comparação disponível.</p>
    @else

        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#f0f0f0;">
                    <th style="padding:10px; border:1px solid #ccc;">Indicador</th>
                    <th style="padding:10px; border:1px solid #ccc;">Previsto</th>
                    <th style="padding:10px; border:1px solid #ccc;">Realizado</th>
                    <th style="padding:10px; border:1px solid #ccc;">%</th>
                    <th style="padding:10px; border:1px solid #ccc;">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($relatorio->resultados as $r)

                    @php
                        $percentual = $r->previsto > 0 
                            ? ($r->realizado / $r->previsto) * 100 
                            : 0;

                        if ($percentual >= 100) {
                            $status = '✔ Excelente';
                            $cor = 'green';
                        } elseif ($percentual >= 70) {
                            $status = '⚠ Parcial';
                            $cor = 'orange';
                        } else {
                            $status = '✖ Baixo';
                            $cor = 'red';
                        }
                    @endphp

                    <tr>
                        <td style="padding:10px; border:1px solid #ccc;">
                            {{ ucfirst(str_replace('_', ' ', $r->campo)) }}
                        </td>

                        <td style="padding:10px; border:1px solid #ccc;">
                            {{ $r->previsto }}
                        </td>

                        <td style="padding:10px; border:1px solid #ccc;">
                            {{ $r->realizado }}
                        </td>

                        <td style="padding:10px; border:1px solid #ccc;">
                            {{ number_format($percentual, 1) }}%
                        </td>

                        <td style="padding:10px; border:1px solid #ccc; color: {{ $cor }}">
                            {{ $status }}
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>

    @endif
</div>