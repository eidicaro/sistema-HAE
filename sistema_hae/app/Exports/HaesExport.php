<?php

namespace App\Exports;

use App\Models\Haes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HaesExport implements FromCollection, WithColumnWidths, WithEvents, WithHeadings, WithMapping, WithStyles
{
    protected $semestreId;

    public function __construct($semestreId)
    {
        $this->semestreId = $semestreId;
    }

    /**
     * Busca as HAEs do semestre selecionado.
     */
    public function collection()
    {
        return Haes::with([
            'user',
            'tipoHae',
            'subtipoHae',
            'semestre',
            'relatores',
        ])
            ->where('semestre_id', $this->semestreId)
            ->orderBy('status')
            ->get();
    }

    /**
     * Cabeçalho da planilha.
     */
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
            'Subtipo HAE',
            'Edital Aceito',
            'Situação',
            'Título da Atividade',
            'Carga Horária Total',
            'Resumo',
            'Justificativa',
            'Resultados Esperados',
            'Indicadores',
            'Horários HAE',
            'Mês 1',
            'Mês 2',
            'Mês 3',
            'Mês 4',
            'Mês 5',
            'Criado em',
            'Atualizado em',
        ];
    }

    /**
     * Formata os dados de cada HAE.
     */
    public function map($hae): array
    {
        return [
            $hae->id,
            $this->formatarTexto(
                $hae->user?->name ?? 'Não informado'
            ),
            $this->formatarTexto(
                $hae->user?->email ?? ''
            ),
            $this->formatarTexto(
                $hae->relatores
                    ->pluck('name')
                    ->implode("\n")
            ),
            $this->formatarTexto($hae->curso),
            $this->formatarTexto(
                $hae->semestre?->nome ?? ''
            ),
            $this->formatarTexto(
                $hae->tipoHae?->nome ?? 'Não informado'
            ),
            $this->formatarTexto(
                $hae->subtipoHae?->nome ?? 'Não informado'
            ),
            $hae->edital_aceito ? 'Sim' : 'Não',
            $this->formatarStatus($hae->status),
            $this->formatarTexto($hae->titulo),
            $hae->carga_horaria !== null
                ? $hae->carga_horaria.' h'
                : '',
            $this->formatarTexto($hae->resumo),
            $this->formatarTexto($hae->justificativa),
            $this->formatarTexto($hae->resultados_esperados),
            $this->formatarTexto($hae->indicadores),
            $this->formatarTexto($hae->horarios_hae),
            $this->formatarTexto($hae->mes_1),
            $this->formatarTexto($hae->mes_2),
            $this->formatarTexto($hae->mes_3),
            $this->formatarTexto($hae->mes_4),
            $this->formatarTexto($hae->mes_5),
            optional($hae->created_at)->format('d/m/Y H:i'),
            optional($hae->updated_at)->format('d/m/Y H:i'),
        ];
    }

    /**
     * Define a largura de cada coluna.
     *
     * Isso é melhor que ShouldAutoSize para campos
     * muito grandes como resumo, justificativa e resultados esperados.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 28,  // Professor
            'C' => 32,  // Email
            'D' => 28,  // Relatores

            'E' => 24,  // Curso
            'F' => 20,  // Período
            'G' => 25,  // Tipo HAE
            'H' => 25,  // Subtipo HAE

            'I' => 15,  // Edital
            'J' => 18,  // Situação

            'K' => 35,  // Título
            'L' => 20,  // Carga horária

            'M' => 50,  // Resumo
            'N' => 50,  // Justificativa
            'O' => 50,  // Resultados esperados
            'P' => 40,  // Indicadores
            'Q' => 35,  // Horários

            'R' => 30,  // Mês 1
            'S' => 30,  // Mês 2
            'T' => 30,  // Mês 3
            'U' => 30,  // Mês 4
            'V' => 30,  // Mês 5

            'W' => 20,  // Criado
            'X' => 20,  // Atualizado
        ];
    }

    /**
     * Estilo geral da planilha.
     */
    public function styles(Worksheet $sheet)
    {
        // Cabeçalho
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF',
                ],
                'size' => 11,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'B30A07',
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],

            'border' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => [
                        'rgb' => '8F0805',
                    ],
                ],
            ],
        ]);

        // Altura do cabeçalho
        $sheet->getRowDimension(1)->setRowHeight(32);

        return [];
    }

    /**
     * Eventos executados depois da criação da planilha.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $highestRow = $sheet->getHighestRow();

                /*
                 * ---------------------------------------------------------
                 * CONFIGURAÇÕES GERAIS
                 * ---------------------------------------------------------
                 */

                // Congela o cabeçalho
                $sheet->freezePane('A2');

                // Filtro automático
                $sheet->setAutoFilter(
                    'A1:X'.$highestRow
                );

                // Zoom
                $sheet->getSheetView()->setZoomScale(85);

                /*
                 * ---------------------------------------------------------
                 * ESTILO DAS CÉLULAS
                 * ---------------------------------------------------------
                 */

                if ($highestRow >= 2) {

                    $sheet->getStyle(
                        'A2:X'.$highestRow
                    )->applyFromArray([

                        'font' => [
                            'name' => 'Aptos',
                            'size' => 10,
                            'color' => [
                                'rgb' => '2D3748',
                            ],
                        ],

                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_TOP,
                            'wrapText' => true,
                        ],

                        'border' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => [
                                    'rgb' => 'E2E8F0',
                                ],
                            ],
                        ],
                    ]);
                }

                /*
                 * ---------------------------------------------------------
                 * LINHAS ALTERNADAS
                 * ---------------------------------------------------------
                 */

                for ($row = 2; $row <= $highestRow; $row++) {

                    // Altura confortável para textos longos
                    $sheet->getRowDimension($row)->setRowHeight(65);

                    // Linhas alternadas
                    if ($row % 2 === 0) {
                        $sheet->getStyle(
                            "A{$row}:X{$row}"
                        )->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'F8FAFC',
                                ],
                            ],
                        ]);
                    }

                    /*
                     * -----------------------------------------------------
                     * STATUS
                     * -----------------------------------------------------
                     */

                    $status = $sheet
                        ->getCell("J{$row}")
                        ->getValue();

                    $statusStyle = match ($status) {

                        'Aprovado' => [
                            'background' => 'DCFCE7',
                            'text' => '166534',
                        ],

                        'Em análise' => [
                            'background' => 'FEF3C7',
                            'text' => '92400E',
                        ],

                        'Reprovado' => [
                            'background' => 'FEE2E2',
                            'text' => '991B1B',
                        ],

                        default => [
                            'background' => 'E2E8F0',
                            'text' => '475569',
                        ],
                    };

                    $sheet->getStyle("J{$row}")
                        ->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => [
                                    'rgb' => $statusStyle['text'],
                                ],
                            ],

                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => $statusStyle['background'],
                                ],
                            ],

                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,

                                'vertical' => Alignment::VERTICAL_CENTER,

                                'wrapText' => true,
                            ],
                        ]);

                    /*
                     * -----------------------------------------------------
                     * EDITAL ACEITO
                     * -----------------------------------------------------
                     */

                    $edital = $sheet
                        ->getCell("I{$row}")
                        ->getValue();

                    if ($edital === 'Sim') {

                        $sheet->getStyle("I{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '166534',
                                    ],
                                ],

                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'DCFCE7',
                                    ],
                                ],

                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                                    'vertical' => Alignment::VERTICAL_CENTER,
                                ],
                            ]);

                    } else {

                        $sheet->getStyle("I{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '991B1B',
                                    ],
                                ],

                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'FEE2E2',
                                    ],
                                ],

                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_CENTER,

                                    'vertical' => Alignment::VERTICAL_CENTER,
                                ],
                            ]);
                    }

                    /*
                     * -----------------------------------------------------
                     * CENTRALIZA CAMPOS CURTOS
                     * -----------------------------------------------------
                     */

                    $sheet->getStyle(
                        "A{$row}:A{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet->getStyle(
                        "F{$row}:J{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                    $sheet->getStyle(
                        "L{$row}:L{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                    $sheet->getStyle(
                        "W{$row}:X{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                }

                /*
                 * ---------------------------------------------------------
                 * TÍTULO EM DESTAQUE
                 * ---------------------------------------------------------
                 */

                $sheet->getStyle(
                    'K2:K'.max(2, $highestRow)
                )->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => '1E293B',
                        ],
                    ],
                ]);

                /*
                 * ---------------------------------------------------------
                 * CONFIGURAÇÕES DE IMPRESSÃO
                 * ---------------------------------------------------------
                 */

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                $sheet->getPageSetup()
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getPageSetup()
                    ->setFitToWidth(1);

                $sheet->getPageSetup()
                    ->setFitToHeight(0);

                $sheet->getPageMargins()->setTop(0.4);
                $sheet->getPageMargins()->setRight(0.3);
                $sheet->getPageMargins()->setBottom(0.4);
                $sheet->getPageMargins()->setLeft(0.3);

                // Nome da aba
                $sheet->setTitle('HAEs');
            },
        ];
    }

    /**
     * Limpa e padroniza os textos vindos do banco.
     */
    private function formatarTexto($texto): string
    {
        if ($texto === null) {
            return '';
        }

        $texto = html_entity_decode(
            strip_tags((string) $texto),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        // Normaliza quebras de linha
        $texto = str_replace(
            ["\r\n", "\r"],
            "\n",
            $texto
        );

        // Remove espaços excessivos em cada linha
        $linhas = explode("\n", $texto);

        $linhas = array_map(
            fn ($linha) => trim(
                preg_replace('/[ \t]+/', ' ', $linha)
            ),
            $linhas
        );

        // Remove linhas vazias repetidas
        $texto = implode("\n", $linhas);

        // Remove excesso de linhas vazias
        $texto = preg_replace(
            "/\n{3,}/",
            "\n\n",
            $texto
        );

        $texto = trim($texto);

        // Impede que conteúdo vindo do usuário seja interpretado como fórmula.
        if (preg_match('/^[=+\-@]/u', $texto) === 1) {
            return "'".$texto;
        }

        return $texto;
    }

    /**
     * Converte o status interno para uma apresentação amigável.
     */
    private function formatarStatus($status): string
    {
        return match ($status) {

            'aprovado' => 'Aprovado',
            'pendente' => 'Em análise',
            'reprovado' => 'Reprovado',

            default => ucfirst($status ?? 'Não informado')
        };
    }
}
