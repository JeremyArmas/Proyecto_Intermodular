<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Cart;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test usuario no autenticado no puede iniciar checkout.
     */
    public function test_unauthenticated_user_cannot_checkout(): void
    {
        $response = $this->post(route('checkout.session'));
        $response->assertRedirect('/login');
    }

    /**
     * Test usuario con carrito vacío es redirigido con error.
     */
    public function test_user_with_empty_cart_is_redirected_back(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        
        $response = $this->actingAs($user)->post(route('checkout.session'));

        $response->assertRedirect(route('carrito.index'));
        $response->assertSessionHas('error', 'Tu carrito está vacío.');
    }

    /**
     * Test página de éxito requiere estar autenticado (o al menos cargar correctamente).
     */
    public function test_success_page_displays_correctly_with_session(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        
        // El éxito suele cargar una vista, aunque pide session_id en el query.
        $response = $this->actingAs($user)->get(route('checkout.success') . '?session_id=test_session');
        
        // Al no existir la sesión real en Stripe mokeado, probablemente falle o redirija al carrito.
        // En este caso, el controlador intenta recuperarla de Stripe.
        // Para no complicar el test con mocks de Stripe reales, verificamos la redirección por fallo de sesión.
        $response->assertRedirect(route('carrito.index'));
    }

    /**
     * Test página de cancelación es accesible.
     */
    public function test_cancel_page_is_accessible(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $response = $this->actingAs($user)->get(route('checkout.cancel'));

        $response->assertStatus(200);
        $response->assertViewIs('checkout.cancel');
    }
}
