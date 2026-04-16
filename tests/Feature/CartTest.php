<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Cart;
use App\Models\CartItem;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test usuario puede añadir un juego al carrito.
     */
    public function test_user_can_add_game_to_cart(): void
    {
        $user = User::factory()->create(['role' => 'company']); // Usamos empresa para probar cantidad > 1
        $game = Game::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->post(route('carrito.add'), [
            'game_id' => $game->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('carrito.index'));
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'game_id' => $game->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test añadir al carrito un producto sin stock suficiente falla.
     */
    public function test_add_to_cart_fails_if_insufficient_stock(): void
    {
        $user = User::factory()->create(['role' => 'company']); // Solo las empresas validan stock físico
        $game = Game::factory()->create(['stock' => 5]);

        $response = $this->actingAs($user)->post(route('carrito.add'), [
            'game_id' => $game->id,
            'quantity' => 6,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('cart_items', ['game_id' => $game->id]);
    }

    /**
     * Test actualizar cantidad en el carrito.
     */
    public function test_user_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create(['role' => 'company']); // Usamos empresa para actualizar a > 1
        $game = Game::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $user->id]);
        $cartItem = $cart->items()->create(['game_id' => $game->id, 'quantity' => 1]);

        $response = $this->actingAs($user)->put(route('carrito.update', $cartItem->id), [
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('carrito.index'));
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 5,
        ]);
    }

    /**
     * Test eliminar un item del carrito.
     */
    public function test_user_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $cartItem = $cart->items()->create(['game_id' => $game->id, 'quantity' => 1]);

        $response = $this->actingAs($user)->delete(route('carrito.remove', $cartItem->id));

        $response->assertRedirect(route('carrito.index'));
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    /**
     * Test vaciar el carrito.
     */
    public function test_user_can_clear_cart(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create();
        $cart = Cart::create(['user_id' => $user->id]);
        $cart->items()->create(['game_id' => $game->id, 'quantity' => 1]);

        $response = $this->actingAs($user)->delete(route('carrito.clear'));

        $response->assertRedirect(route('carrito.index'));
        $this->assertEquals(0, $cart->items()->count());
    }

    /**
     * Test añadir el mismo producto dos veces incrementa la cantidad.
     */
    public function test_adding_same_game_twice_increments_quantity(): void
    {
        $user = User::factory()->create(['role' => 'company']); // Solo las empresas acumulan cantidad
        $game = Game::factory()->create(['stock' => 10]);

        $this->actingAs($user)->post(route('carrito.add'), ['game_id' => $game->id, 'quantity' => 1]);
        $this->actingAs($user)->post(route('carrito.add'), ['game_id' => $game->id, 'quantity' => 2]);

        $this->assertDatabaseHas('cart_items', [
            'game_id' => $game->id,
            'quantity' => 3,
        ]);
    }
}
