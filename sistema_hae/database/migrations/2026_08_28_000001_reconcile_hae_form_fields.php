<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Acrescenta somente os campos ausentes em instalações já existentes.
     */
    public function up(): void
    {
        $campos = [
            'resultados_esperados',
            'indicadores',
            'mes_1',
            'mes_2',
            'mes_3',
            'mes_4',
            'mes_5',
            'horarios_hae',
        ];

        foreach ($campos as $campo) {
            if (! Schema::hasColumn('haes', $campo)) {
                Schema::table('haes', function (Blueprint $table) use ($campo): void {
                    $table->text($campo)->nullable();
                });
            }
        }

        if (Schema::hasColumn('haes', 'especificacoes')) {
            Schema::table('haes', function (Blueprint $table): void {
                $table->text('especificacoes')->nullable()->change();
            });
        }
    }

    /**
     * Não remove campos: eles podem ser anteriores ao histórico local da migration.
     */
    public function down(): void
    {
        // Reversão deliberadamente não destrutiva.
    }
};
