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
        Schema::create('hae_plantao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hae_id')->constrained('haes')->onDelete('cascade');
        
            $table->string('tipo_plantao')->nullable();
        
            $table->integer('alunos_atendidos')->nullable();
            $table->integer('simulados')->nullable();
            $table->integer('relatorios')->nullable();
            $table->integer('acoes')->nullable();
        
            $table->string('indicador')->nullable();
            $table->string('outra_acao')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hae_plantaos');
    }
};
