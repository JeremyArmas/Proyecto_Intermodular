<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrator;
use App\Models\Game;
use App\Models\Cart;
use App\Models\Wishlist;

/**
 * Tests que cubren el bug fix: el administrador podía ser redirigido
 * al login al intentar usar el carrito o la wishlist desde la tienda,
 * ya que las rutas solo aceptaban el guard 'web' y el admin usa 'admin'.
 */
class AdminStoreTest extends TestCase
{
    use RefreshDatabase;

    // ───────────────────────────────────────────
    // CARRITO
    // ───────────────────────────────────────────

    /**
     * Un admin autenticado puede acceder a la página del carrito.
     */
    public function test_admin_can_view_cart_page(): void
    {
        $admin = Administrator::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('carrito.index'));

        $response->assertStatus(200);
    }

    /**
     * Un invitado es redirigido al intentar acceder al carrito.
     */
    public function test_guest_cannot_access_cart(): void
    {
        $response = $this->get(route('carrito.index'));

        // Debe redirigir (no 200), ya que la ruta está protegida
        $response->assertRedirect();
    }

    // ───────────────────────────────────────────
    // WISHLIST
    // ───────────────────────────────────────────

    /**
     * Un admin puede añadir un juego a su lista de deseos.
     */
    public function test_admin_can_add_game_to_wishlist(): void
    {
        $admin = Administrator::factory()->create();
        $game  = Game::factory()->create(['is_active' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('wishlist.store'), [
            'game_id' => $game->id,
        ]);

        // La ruta redirige (back) o devuelve JSON; en cualquier caso el registro debe existir
        $this->assertTrue(
            $response->isRedirection() || $response->isSuccessful(),
            'La respuesta debería ser una redirección o un 2xx.'
        );

        $this->assertDatabaseHas('wishlists', [
            'administrator_id' => $admin->id,
            'game_id'          => $game->id,
        ]);
    }

    /**
     * Añadir el mismo juego dos veces lo elimina (toggle).
     */
    public function test_admin_wishlist_toggles_on_duplicate(): void
    {
        $admin = Administrator::factory()->create();
        $game  = Game::factory()->create(['is_active' => true]);

        // Primera vez: añade
        $this->actingAs($admin, 'admin')->post(route('wishlist.store'), ['game_id' => $game->id]);
        $this->assertDatabaseHas('wishlists', ['administrator_id' => $admin->id, 'game_id' => $game->id]);

        // Segunda vez: elimina (toggle)
        $this->actingAs($admin, 'admin')->post(route('wishlist.store'), ['game_id' => $game->id]);
        $this->assertDatabaseMissing('wishlists', ['administrator_id' => $admin->id, 'game_id' => $game->id]);
    }

    /**
     * Un invitado no puede acceder a la wishlist.
     */
    public function test_guest_cannot_access_wishlist(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect();
    }

    // ───────────────────────────────────────────
    // CHECKOUT
    // ───────────────────────────────────────────

    /**
     * Un admin con carrito vacío es redirigido con mensaje de error.
     */
    public function test_admin_with_empty_cart_is_redirected_from_checkout(): void
    {
        $admin = Administrator::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('checkout.session'));

        $response->assertRedirect(route('carrito.index'));
        $response->assertSessionHas('error', 'Tu carrito está vacío.');
    }

    /**
     * Un invitado no puede iniciar el checkout.
     */
    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->post(route('checkout.session'));

        $response->assertRedirect();
    }
}
