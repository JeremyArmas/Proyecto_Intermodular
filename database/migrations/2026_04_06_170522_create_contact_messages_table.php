<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*Esta Tabla almacena los mensajes de contacto enviados por los clientes desde la web*/

return new class extends Migration {

    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) { // Datos de contacto enviados por clientes desde la web
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->foreignId('admin_id')->nullable()->constrained('users'); // Admin que atiende el ticket
            $table->timestamp('locked_at')->nullable(); // Para bloquear el ticket mientras un admin lo atiende
            $table->enum('status', ['pendiente', 'en_proceso', 'finalizado'])->default('pendiente'); // Estado del ticket
            $table->timestamps(); // created_at = fecha de envío del mensaje, updated_at = fecha de última actualización del ticket (ej: respuesta del admin)
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
