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
        Schema::create('hae_graduacao', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('hae_id')->constrained('haes')->onDelete('cascade');
        
            $table->string('tipo_graduacao');
            $table->string('orientacoes')->nullable();
            $table->integer('bancas_orientador')->nullable();
            $table->integer('bancas_membro')->nullable();
            $table->string('indicador')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hae_graduacaos');
    }
};
