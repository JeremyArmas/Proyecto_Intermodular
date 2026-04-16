<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Game;
use App\Models\Category;
use App\Models\Platform;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the base catalog page loads and displays active games.
     */
    public function test_catalog_displays_active_games(): void
    {
        $activeGame = Game::factory()->create(['is_active' => true, 'title' => 'Active Super Game']);
        $inactiveGame = Game::factory()->create(['is_active' => false, 'title' => 'Hidden Secret Game']);

        $response = $this->get(route('catalogo'));

        $response->assertStatus(200);
        $response->assertSee('Active Super Game');
        $response->assertDontSee('Hidden Secret Game');
    }

    /**
     * Test searching by name returns the correct game.
     */
    public function test_catalog_can_be_searched_by_name(): void
    {
        Game::factory()->create(['is_active' => true, 'title' => 'Zelda Ocarina']);
        Game::factory()->create(['is_active' => true, 'title' => 'Mario Kart']);

        $response = $this->get(route('catalogo', ['search' => 'Zelda']));

        $response->assertStatus(200);
        $response->assertSee('Zelda Ocarina');
        $response->assertDontSee('Mario Kart');
    }

    /**
     * Test filtering games by platform.
     */
    public function test_catalog_can_filter_by_platform(): void
    {
        $platformPS5 = Platform::factory()->create(['slug' => 'playstation-5', 'name' => 'PS5']);
        $platformPC = Platform::factory()->create(['slug' => 'pc', 'name' => 'PC']);

        Game::factory()->create(['is_active' => true, 'title' => 'God of War', 'platform_id' => $platformPS5->id]);
        Game::factory()->create(['is_active' => true, 'title' => 'Counter Strike', 'platform_id' => $platformPC->id]);

        $response = $this->get(route('catalogo', ['platform' => $platformPS5->slug]));

        $response->assertStatus(200);
        $response->assertSee('God of War');
        $response->assertDontSee('Counter Strike');
    }

    /**
     * Test filtering games by category.
     */
    public function test_catalog_can_filter_by_category(): void
    {
        $categoryAction = Category::factory()->create(['slug' => 'accion', 'name' => 'Accion']);
        $categorySports = Category::factory()->create(['slug' => 'deportes', 'name' => 'Deportes']);

        $gameAction = Game::factory()->create(['is_active' => true, 'title' => 'Super Action Game']);
        $gameAction->categories()->attach($categoryAction->id);

        $gameSports = Game::factory()->create(['is_active' => true, 'title' => 'FIFA 29']);
        $gameSports->categories()->attach($categorySports->id);

        $response = $this->get(route('catalogo', ['category' => $categoryAction->slug]));

        $response->assertStatus(200);
        $response->assertSee('Super Action Game');
        $response->assertDontSee('FIFA 29');
    }

    /**
     * Test active individual game page displays correctly.
     */
    public function test_can_view_individual_active_game_page(): void
    {
        $game = Game::factory()->create(['is_active' => true, 'slug' => 'test-game-123']);

        $response = $this->get(route('juego.show', $game->slug));

        $response->assertStatus(200);
        $response->assertSee($game->title);
    }

    /**
     * Test inactive individual game page cannot be viewed normally (404).
     */
    public function test_cannot_view_inactive_game_page(): void
    {
        $game = Game::factory()->create(['is_active' => false, 'slug' => 'hidden-game']);

        $response = $this->get(route('juego.show', $game->slug));

        $response->assertStatus(404);
    }
}
