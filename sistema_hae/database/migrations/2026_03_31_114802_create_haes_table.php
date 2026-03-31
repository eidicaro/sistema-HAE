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
            $table->string('tipo');

            $table->string('curso');
            $table->string('titulo');
            $table->integer('carga_horaria');
        
            $table->text('resumo');
            $table->text('justificativa');
        
            // cronograma
            $table->text('fevereiro')->nullable();
            $table->text('marco')->nullable();
            $table->text('abril')->nullable();
            $table->text('maio')->nullable();
            $table->text('junho')->nullable();
        
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
