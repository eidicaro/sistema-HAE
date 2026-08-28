<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('haes', function (Blueprint $table) {
            $table->id();
            $table->boolean('edital_aceito');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // usuario
            $table->foreignId('semestre_id')->constrained()->cascadeOnDelete(); // semestres
            $table->foreignId('tipo_hae_id')->constrained('tipo_haes')->restrictOnDelete();

            // infos gerais
            $table->string('curso');
            $table->string('titulo');
            $table->integer('carga_horaria');
            $table->text('resumo');
            $table->text('justificativa');
            $table->text('resultados_esperados')->nullable();
            $table->text('indicadores')->nullable();

            // desenvolvimento previsto por mês
            $table->text('mes_1')->nullable();
            $table->text('mes_2')->nullable();
            $table->text('mes_3')->nullable();
            $table->text('mes_4')->nullable();
            $table->text('mes_5')->nullable();

            // dias e horários de execução
            $table->text('horarios_hae')->nullable();

            $table->string('status')->default('pendente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('haes');
    }
};
