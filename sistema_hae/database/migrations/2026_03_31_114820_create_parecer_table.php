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
        Schema::create('parecer', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('hae_id')->constrained('haes')->onDelete('cascade');
            $table->foreignId('relator_id')->constrained('users')->onDelete('cascade');
        
            $table->text('comentario');
        
            $table->timestamps();
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
