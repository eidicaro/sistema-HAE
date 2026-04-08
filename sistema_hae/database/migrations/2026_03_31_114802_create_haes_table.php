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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semestre_id')->constrained()->cascadeOnDelete(); //semestres
            $table->string('tipo');

            $table->string('curso');
            $table->string('titulo');
            $table->integer('carga_horaria');
        
            $table->text('resumo');
            $table->text('justificativa');
        
            // cronograma
            $table->date('fevereiro')->nullable();
            $table->date('marco')->nullable();
            $table->date('abril')->nullable();
            $table->date('maio')->nullable();
            $table->date('junho')->nullable();
        
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
