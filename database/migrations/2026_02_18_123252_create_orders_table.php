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
    // TABLA MAESTRA DE PEDIDOS
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Estado del pedido: Pendiente, Pagado, Enviado, Cancelado
        $table->enum('status', ['pending', 'paid', 'shipped', 'cancelled'])->default('pending');
        $table->string('stripe_session_id')->nullable(); // ID de sesión de Stripe para pagos
        
        // Total dinero del pedido
        $table->decimal('total_amount', 10, 2);
        
        // Guardamos la dirección de envío en el momento de la compra (por si el usuario se muda después)
        $table->text('shipping_address')->nullable();
        
        // Tipo de pedido (Para tus estadísticas: ¿Venta normal o Mayorista?)
        $table->string('order_type')->default('b2c'); // 'b2c' (Cliente) o 'b2b' (Empresa)
        
        $table->timestamps();
    });

    // TABLA DETALLE (Qué juegos iban en ese pedido)
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('game_id')->constrained();
        
        $table->integer('quantity');
        
        // IMPORTANTE: Guardamos el precio al que se vendió. 
        $table->decimal('price_at_purchase', 10, 2);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
