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

        //Añadimos campos para el seguimiento de los pedidos. Los pedidos digitales , los de los usuarios normales no tiene rastreo ya que
        //los reciben automáticamente en sys bibliotecas.

        Schema::table('orders', function (Blueprint $table) { 
            $table->enum('tracking_status', [
                'in_warehouse', //Pedido en almacén
                'shipped_out', //Enviado por nosotros hacia la empresa de reparto
                'with_courier', //En reparto (por mensajero)
                'on_the_way', //En camino (por mensajero) a la empresa que lo compró
                'delivered', //Entregado a la empresa
            ])->nullable()->after('order_type');

            $table->timestamp('delivered_confirmed_at')->nullable()->after('tracking_status'); 
            //Fecha en la que el usuario confirma la entrega. Se rellena cuando la empresa confirma.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) { 
            $table->dropColumn(['tracking_status', 'delivered_confirmed_at']); //Eliminamos los campos por si se quiere revertir la migración.
        });
    }
};
