<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Category;

class AdminGameCrudExtendedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de juegos.
     */
    public function test_admin_can_view_games_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Game::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.games.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede ver el formulario de crear juego.
     */
    public function test_admin_can_view_create_game_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.games.create'));

        $response->assertStatus(200);
    }


    /**
     * Test crear juego sin título falla con validación.
     */
    public function test_create_game_requires_title(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $platform = Platform::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.games.store'), [
            'title' => '',
            'slug' => 'test-game',
            'price' => 59.99,
            'stock' => 10,
            'platform_id' => $platform->id,
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * Test crear juego sin precio falla con validación.
     */
    public function test_create_game_requires_price(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $platform = Platform::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.games.store'), [
            'title' => 'Test Game',
            'slug' => 'test-game',
            'price' => '',
            'stock' => 10,
            'platform_id' => $platform->id,
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('price');
    }

    /**
     * Test un cliente no puede crear juegos.
     */
    public function test_client_cannot_create_game(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $platform = Platform::factory()->create();

        $response = $this->actingAs($client)->post(route('admin.games.store'), [
            'title' => 'Hack Game',
            'slug' => 'hack-game',
            'price' => 0.01,
            'stock' => 999,
            'platform_id' => $platform->id,
            'is_active' => 1,
        ]);

        $response->assertRedirect('/');
    }

    /**
     * Test un cliente no puede eliminar juegos.
     */
    public function test_client_cannot_delete_game(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $game = Game::factory()->create();

        $response = $this->actingAs($client)->delete(route('admin.games.destroy', $game));

        $response->assertRedirect('/');
        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    /**
     * Test usuario no autenticado no puede acceder al CRUD de juegos.
     */
    public function test_unauthenticated_user_cannot_access_games_crud(): void
    {
        $response = $this->get(route('admin.games.index'));

        $response->assertRedirect('/login');
    }
}
