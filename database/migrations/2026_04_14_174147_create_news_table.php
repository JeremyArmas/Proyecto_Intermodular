<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void // Crea la tabla 
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('title'); // Titulo de la noticia
            $table->text('content'); // Contenido de la noticia
            $table->string('image')->nullable(); // Portada de la noticia
            $table->boolean('is_published')->default(true); // Estado de la noticia. Para que desde el panel de admin se pueda publicar o despublicar una noticia.
            $table->timestamps(); // Fecha de creacion y actualizacion
        });
    }

    public function down(): void // Borra la tabla si existe
    {
        Schema::dropIfExists('news');
    }
};
