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
        Schema::create('hae_administracao', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('hae_id')->constrained('haes')->onDelete('cascade');
        
            $table->string('tipo_admin')->nullable();
        
            $table->integer('encontros')->nullable();
            $table->integer('relatorios')->nullable();
            $table->integer('acoes_interdisciplinares')->nullable();
            $table->integer('formacoes')->nullable();
            $table->integer('materiais')->nullable();
        
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
        Schema::dropIfExists('hae_administracaos');
    }
};
