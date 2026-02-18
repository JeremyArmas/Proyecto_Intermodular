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
    Schema::create('games', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('description');
        
        // PRECIOS
        $table->decimal('price', 10, 2); // Precio normal
        $table->decimal('b2b_price', 10, 2)->nullable(); // Precio para empresas
        
        $table->integer('stock')->default(0);
        $table->string('cover_image')->nullable(); // Foto portada
        $table->string('developer')->nullable(); 
        
        // Un juego pertenece a UNA plataforma (Físico o Digital)
        $table->foreignId('platform_id')->constrained(); 
        
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    // Tabla pivote: Un juego puede tener MUCHAS categorías
    Schema::create('category_game', function (Blueprint $table) {
        $table->id();
        $table->foreignId('game_id')->constrained()->onDelete('cascade');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
