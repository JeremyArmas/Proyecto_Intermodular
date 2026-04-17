<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Wishlist;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    // ───────────────────────────────────────────
    // USUARIO NORMAL (cliente)
    // ───────────────────────────────────────────

    /**
     * Un usuario autenticado puede añadir un juego a su wishlist.
     */
    public function test_user_can_add_game_to_wishlist(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create(['is_active' => true]);

        $response = $this->actingAs($user)->post(route('wishlist.store'), [
            'game_id' => $game->id,
        ]);

        // Acepta tanto redirección (normal) como JSON (AJAX)
        $this->assertTrue(
            $response->isRedirection() || $response->isSuccessful(),
            'La respuesta debería ser una redirección o un 2xx.'
        );

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'game_id' => $game->id,
        ]);
    }

    /**
     * Añadir el mismo juego dos veces lo quita (toggle).
     */
    public function test_wishlist_toggles_off_when_game_already_added(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create(['is_active' => true]);

        // Primera llamada → añade
        $this->actingAs($user)->post(route('wishlist.store'), ['game_id' => $game->id]);
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'game_id' => $game->id]);

        // Segunda llamada → quita (toggle)
        $this->actingAs($user)->post(route('wishlist.store'), ['game_id' => $game->id]);
        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'game_id' => $game->id]);
    }

    /**
     * Un usuario puede eliminar un ítem de su wishlist.
     */
    public function test_user_can_remove_item_from_wishlist(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create(['is_active' => true]);

        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'is_read' => false,
        ]);

        $response = $this->actingAs($user)->delete(route('wishlist.destroy', $wishlistItem->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('wishlists', ['id' => $wishlistItem->id]);
    }

    /**
     * Un usuario no puede borrar la wishlist de otro usuario.
     */
    public function test_user_cannot_delete_another_users_wishlist_item(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $other = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create(['is_active' => true]);

        $wishlistItem = Wishlist::create([
            'user_id' => $owner->id,
            'game_id' => $game->id,
            'is_read' => false,
        ]);

        $response = $this->actingAs($other)->delete(route('wishlist.destroy', $wishlistItem->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('wishlists', ['id' => $wishlistItem->id]);
    }

    /**
     * Un usuario puede ver su lista de deseos.
     */
    public function test_user_can_view_wishlist_page(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist');
    }

    // ───────────────────────────────────────────
    // INVITADO
    // ───────────────────────────────────────────

    /**
     * Un invitado no puede ver la wishlist.
     */
    public function test_guest_cannot_view_wishlist(): void
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect();
    }

    /**
     * Un invitado no puede añadir a la wishlist.
     */
    public function test_guest_cannot_add_to_wishlist(): void
    {
        $game = Game::factory()->create(['is_active' => true]);

        $response = $this->post(route('wishlist.store'), ['game_id' => $game->id]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('wishlists', ['game_id' => $game->id]);
    }

    // ───────────────────────────────────────────
    // EMPRESA (b2b)
    // ───────────────────────────────────────────

    /**
     * Un usuario empresa también puede añadir a wishlist.
     */
    public function test_company_user_can_add_game_to_wishlist(): void
    {
        $user = User::factory()->create(['role' => 'company']);
        $game = Game::factory()->create(['is_active' => true]);

        $this->actingAs($user)->post(route('wishlist.store'), ['game_id' => $game->id]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'game_id' => $game->id,
        ]);
    }
}
