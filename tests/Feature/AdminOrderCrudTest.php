<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class AdminOrderCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de pedidos.
     */
    public function test_admin_can_view_orders_index(): void
    {
        $this->loginAsAdmin();
        Order::factory()->count(3)->create();

        $response = $this->get(route('admin.orders.index'));

        $response->assertStatus(200);
    }


    /**
     * Test admin puede cambiar estado de pedido a 'paid'.
     */
    public function test_admin_can_change_order_status_to_paid(): void
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->put(route('admin.orders.update', $order), [
            'status' => 'paid',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }

    /**
     * Test admin puede cambiar estado de pedido a 'shipped'.
     */
    public function test_admin_can_change_order_status_to_shipped(): void
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create(['status' => 'paid']);

        $response = $this->put(route('admin.orders.update', $order), [
            'status' => 'shipped',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'shipped']);
    }

    /**
     * Test admin puede cambiar estado de pedido a 'cancelled'.
     */
    public function test_admin_can_change_order_status_to_cancelled(): void
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->put(route('admin.orders.update', $order), [
            'status' => 'cancelled',
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    /**
     * Test admin no puede asignar un estado inválido a un pedido.
     */
    public function test_admin_cannot_set_invalid_order_status(): void
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->put(route('admin.orders.update', $order), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending']);
    }

    /**
     * Test admin puede eliminar un pedido.
     */
    public function test_admin_can_delete_order(): void
    {
        $this->loginAsAdmin();
        $order = Order::factory()->create();

        $response = $this->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    /**
     * Test la creación manual de pedidos no está habilitada.
     */
    public function test_manual_order_creation_is_disabled(): void
    {
        $this->loginAsAdmin();
 
        // Intentamos acceder a una ruta que no existe por diseño
        $response = $this->get('/admin/orders/create');
 
        $response->assertStatus(404);
    }

    /**
     * Test un cliente no puede acceder al CRUD de pedidos.
     */
    public function test_client_cannot_access_orders_crud(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('admin.orders.index'));

        $response->assertRedirect('/login');
    }

    /**
     * Test usuario no autenticado no puede acceder a los pedidos.
     */
    public function test_unauthenticated_user_cannot_access_orders(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertRedirect('/login');
    }
}
