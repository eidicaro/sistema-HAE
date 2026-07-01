<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de HAE</title>

    <link rel="stylesheet" href="{{ asset('../css/fonte.css') }}">
    <link rel="stylesheet" href="{{ asset('../css/tipos-hae.css') }}">
</head>

<body>

    @include('components.header')

    <div class="container">

        <div class="topo">
            <h1>Tipos de HAE</h1>

            <div class="botoes-topo">
                <a href="{{ route('direcao') }}" class="btn-voltar">
                    ← Voltar
                </a>

                <a href="{{ route('direcao.tipos-hae.create') }}" class="btn-novo">
                    + Novo Tipo
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert sucesso">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert erro">
            {{ session('error') }}
        </div>
        @endif

        <table class="tabela">

            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Limite</th>
                    <th>Status</th>
                    <th width="230">Ações</th>
                </tr>
            </thead>

            <tbody>

                @forelse($tipos as $tipo)

                <tr>

                    <td>{{ $tipo->nome }}</td>

                    <td>
                        {{ $tipo->descricao ?: '-' }}
                    </td>

                    <td>{{ $tipo->limite }} h</td>

                    <td>

                        @if($tipo->ativo)
                        <span class="status ativo">
                            Ativo
                        </span>
                        @else
                        <span class="status inativo">
                            Inativo
                        </span>
                        @endif

                    </td>

                    <td>

                        <div class="acoes">

                            <a href="{{ route('direcao.tipos-hae.edit', $tipo) }}">
                                Editar
                            </a>

                            <form action="{{ route('direcao.tipos-hae.toggle', $tipo) }}" method="POST">
                                @csrf

                                <button type="submit">

                                    {{ $tipo->ativo ? 'Desativar' : 'Ativar' }}

                                </button>

                            </form>

                            <form
                                action="{{ route('direcao.tipos-hae.destroy', $tipo) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja realmente excluir este tipo?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-excluir">

                                    Excluir

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="vazio">

                        Nenhum tipo de HAE cadastrado.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</body>

</html>