@php
    $mensagens = [
        'success' => ['tipo' => 'success', 'titulo' => 'Tudo certo'],
        'sucesso' => ['tipo' => 'success', 'titulo' => 'Tudo certo'],
        'error' => ['tipo' => 'error', 'titulo' => 'Não foi possível concluir'],
        'erro' => ['tipo' => 'error', 'titulo' => 'Não foi possível concluir'],
    ];
@endphp

@foreach($mensagens as $chave => $configuracao)
    @if(session($chave))
        <div class="alert alert--{{ $configuracao['tipo'] }}" role="alert">
            <span class="alert__dot"></span>
            <div><strong>{{ $configuracao['titulo'] }}</strong><p>{{ session($chave) }}</p></div>
        </div>
    @endif
@endforeach

@if($errors->any())
    <div class="alert alert--error" role="alert">
        <span class="alert__dot"></span>
        <div>
            <strong>Revise os dados informados</strong>
            <ul>
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
