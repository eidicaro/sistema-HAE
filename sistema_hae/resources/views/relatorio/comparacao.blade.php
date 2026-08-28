<section class="content-block">
    <h3>Comparação de resultados</h3>
    @if(!$relatorio || !$relatorio->relationLoaded('resultados') || $relatorio->resultados->isEmpty())
        <div class="empty-state"><p>Nenhum indicador quantitativo informado.</p></div>
    @else
        <div class="table-wrap"><table class="data-table">
            <thead><tr><th>Indicador</th><th>Previsto</th><th>Realizado</th><th>Resultado</th></tr></thead>
            <tbody>
                @foreach($relatorio->resultados as $resultado)
                    @php
                        $percentual = $resultado->previsto > 0 ? ($resultado->realizado / $resultado->previsto) * 100 : 0;
                        $tom = $percentual >= 100 ? 'tag--active' : ($percentual >= 70 ? 'tag--warning' : 'tag--danger');
                        $rotulo = $percentual >= 100 ? 'Meta alcançada' : ($percentual >= 70 ? 'Parcial' : 'Abaixo do previsto');
                    @endphp
                    <tr>
                        <td><strong>{{ ucfirst(str_replace('_', ' ', $resultado->campo)) }}</strong></td>
                        <td>{{ $resultado->previsto }}</td>
                        <td>{{ $resultado->realizado }}</td>
                        <td><span class="tag {{ $tom }}">{{ number_format($percentual, 1) }}% · {{ $rotulo }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    @endif
</section>
