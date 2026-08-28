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
        Schema::create('relatorio_resultados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relatorio_id')->constrained()->onDelete('cascade');

            $table->string('campo'); // ex: escolas, eventos
            $table->integer('previsto')->nullable();
            $table->integer('realizado')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorio_resultados');
    }
};
