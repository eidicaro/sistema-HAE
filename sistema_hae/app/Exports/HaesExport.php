<?php

namespace App\Exports;

use App\Models\Haes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HaesExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected $semestreId;

    public function __construct($semestreId)
    {
        $this->semestreId = $semestreId;
    }


    public function collection()
    {
        return Haes::with([
            'user',
            'tipoHae',
            'semestre',
            'relatores',
        ])
            ->where('semestre_id', $this->semestreId)
            ->orderBy('status')
            ->get();
    }


    public function headings(): array
    {
        return [
            'ID',
            'Professor Responsável',
            'Email do Professor',
            'Relatores',

            'Curso',
            'Período Letivo',
            'Tipo HAE',

            'Edital Aceito',
            'Situação',

            'Título da Atividade',
            'Carga Horária Total',

            'Resumo',
            'Justificativa',
            'Especificações',
            'Cronograma',

            'Indicadores',
            'Horários HAE',

            'Mês 1',
            'Mês 2',
            'Mês 3',
            'Mês 4',
            'Mês 5',

            'Criado em',
            'Atualizado em'
        ];
    }


    public function map($hae): array
    {
        return [
            $hae->id,

            $hae->user?->name ?? 'Não informado',

            $hae->user?->email ?? '',

            $hae->relatores
                ->pluck('name')
                ->implode(' | '),


            $hae->curso,

            $hae->semestre?->nome ?? '',

            $hae->tipoHae?->nome ?? 'Não informado',


            $hae->edital_aceito ? 'Sim' : 'Não',

            $this->formatarStatus($hae->status),


            $hae->titulo,

            $hae->carga_horaria . ' horas',


            $hae->resumo,

            $hae->justificativa,

            $hae->especificacoes ?? '',

            $hae->cronograma ?? '',


            $hae->indicadores ?? '',

            $hae->horarios_hae ?? '',


            $hae->mes_1 ?? '',

            $hae->mes_2 ?? '',

            $hae->mes_3 ?? '',

            $hae->mes_4 ?? '',

            $hae->mes_5 ?? '',


            optional($hae->created_at)->format('d/m/Y H:i'),

            optional($hae->updated_at)->format('d/m/Y H:i'),
        ];
    }


    private function formatarStatus($status)
    {
        return match ($status) {

            'aprovado' => 'Aprovado',

            'pendente' => 'Em análise',

            'reprovado' => 'Reprovado',

            default => ucfirst($status)
        };
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true
                ]
            ]
        ];
    }
}
