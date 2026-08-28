<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>HAE {{ $hae->id }} — {{ $hae->titulo }}</title>
    <style>
        @page { margin: 24mm 18mm 20mm; }
        body { margin: 0; color: #202124; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.45; }
        .header { margin-bottom: 22px; padding-bottom: 12px; border-bottom: 3px solid #b51217; }
        .institution { color: #b51217; font-size: 11px; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; }
        h1 { margin: 5px 0 2px; color: #202124; font-size: 20px; }
        .subtitle { margin: 0; color: #62666d; }
        h2 { margin: 20px 0 8px; padding-left: 8px; border-left: 3px solid #b51217; color: #991015; font-size: 13px; }
        .meta { width: 100%; border-collapse: collapse; }
        .meta td { width: 50%; padding: 7px 8px; border: 1px solid #d8dadd; vertical-align: top; }
        .label { display: block; margin-bottom: 2px; color: #666a70; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .text-block { margin: 0; padding: 10px; border: 1px solid #d8dadd; background: #fafafa; white-space: pre-wrap; }
        .schedule { width: 100%; border-collapse: collapse; }
        .schedule th { padding: 7px; color: #fff; background: #b51217; text-align: left; }
        .schedule td { padding: 8px; border: 1px solid #d8dadd; vertical-align: top; }
        .schedule td:first-child { width: 55px; font-weight: bold; }
        .footer { position: fixed; right: 0; bottom: -12mm; left: 0; color: #777; font-size: 8px; text-align: center; }
    </style>
</head>
<body>
    @php
        $statusLabel = match($hae->status) {
            'pendente' => 'Pendente',
            'com_diligencia' => 'Em diligência',
            'em_execucao' => 'Em execução',
            'finalizada' => 'Finalizada',
            'recusada' => 'Recusada',
            default => $hae->status,
        };
    @endphp
    <div class="footer">Sistema de Gerenciamento de HAE · Documento gerado em {{ now()->format('d/m/Y H:i') }}</div>

    <header class="header">
        <div class="institution">Fatec · Horas-Atividade Específicas</div>
        <h1>{{ $hae->titulo }}</h1>
        <p class="subtitle">Proposta HAE nº {{ $hae->id }}</p>
    </header>

    <h2>Identificação</h2>
    <table class="meta">
        <tr>
            <td><span class="label">Professor responsável</span>{{ $hae->user->name }}</td>
            <td><span class="label">E-mail</span>{{ $hae->user->email }}</td>
        </tr>
        <tr>
            <td><span class="label">Tipo de HAE</span>{{ $hae->tipoHae->nome ?? 'Não informado' }}</td>
            <td><span class="label">Subtipo</span>{{ $hae->subtipoHae->nome ?? 'Registro anterior à definição de subtipos' }}</td>
        </tr>
        <tr>
            <td><span class="label">Curso</span>{{ $hae->curso }}</td>
            <td><span class="label">Semestre</span>{{ $hae->semestre->nome ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td><span class="label">Carga horária semanal</span>{{ $hae->carga_horaria }}h</td>
            <td><span class="label">Data de submissão</span>{{ $hae->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><span class="label">Situação</span>{{ $statusLabel }}</td>
            <td><span class="label">Edital</span>{{ $hae->edital_aceito ? 'Lido e aceito' : 'Não aceito' }}</td>
        </tr>
    </table>

    <h2>Resumo do projeto</h2>
    <p class="text-block">{{ $hae->resumo }}</p>

    <h2>Justificativa do projeto</h2>
    <p class="text-block">{{ $hae->justificativa }}</p>

    <h2>Resultados esperados</h2>
    <p class="text-block">{{ $hae->resultados_esperados ?: 'Não informado.' }}</p>

    <h2>Indicadores</h2>
    <p class="text-block">{{ $hae->indicadores ?: 'Não informado.' }}</p>

    <h2>Cronograma</h2>
    <table class="schedule">
        <thead><tr><th>Mês</th><th>Desenvolvimento previsto</th></tr></thead>
        <tbody>
            @for($i = 1; $i <= 5; $i++)
                <tr><td>Mês {{ $i }}</td><td>{{ $hae->{'mes_'.$i} ?: 'Não informado.' }}</td></tr>
            @endfor
        </tbody>
    </table>

    <h2>Horários da HAE</h2>
    <p class="text-block">{{ $hae->horarios_hae ?: 'Não informado.' }}</p>
</body>
</html>
