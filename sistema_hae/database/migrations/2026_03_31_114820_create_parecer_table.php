<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('parecer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hae_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['relator', 'coordenador']);
            $table->text('comentario');
            $table->timestamps();
    
            // evita duplicidade de parecer por usuário
            $table->unique(['hae_id', 'user_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parecers');
    }
};
