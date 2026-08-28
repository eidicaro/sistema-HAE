<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtipo_haes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_hae_id')->constrained('tipo_haes')->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['tipo_hae_id', 'nome']);
        });

        Schema::table('haes', function (Blueprint $table) {
            $table->foreignId('subtipo_hae_id')
                ->nullable()
                ->after('tipo_hae_id')
                ->constrained('subtipo_haes')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('haes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subtipo_hae_id');
        });

        Schema::dropIfExists('subtipo_haes');
    }
};
