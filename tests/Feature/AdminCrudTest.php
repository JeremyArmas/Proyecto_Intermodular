<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Order;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access panel.
     */
    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Panel de administración');
    }

    /**
     * Test normal user cannot access panel.
     */
    public function test_normal_user_cannot_access_panel(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get('/admin');

        $response->assertRedirect('/');
    }

    /**
     * Test unauthenticated user cannot access panel.
     */
    public function test_unauthenticated_user_cannot_access_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /**
     * Game CRUD Tests
     */
    public function test_admin_can_create_game(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $platform = Platform::factory()->create();
        $category = Category::factory()->create();

        $gameData = [
            'title' => 'New Test Game',
            'slug' => 'new-test-game',
            'description' => 'Test Description',
            'price' => 59.99,
            'stock' => 10,
            'platform_id' => $platform->id,
            'categories' => [$category->id],
            'is_active' => 1
        ];

        $response = $this->actingAs($admin)->post(route('admin.games.store'), $gameData);

        $response->assertRedirect(route('admin.panel', ['#productos']));
        $this->assertDatabaseHas('games', ['title' => 'New Test Game']);
    }

    public function test_admin_can_update_game(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $game = Game::factory()->create(['title' => 'Old Title']);

        $response = $this->actingAs($admin)->put(route('admin.games.update', $game), [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'price' => 49.99,
            'stock' => 5,
            'platform_id' => $game->platform_id,
            'is_active' => 1
        ]);

        $response->assertRedirect(route('admin.panel', ['#productos']));
        $this->assertDatabaseHas('games', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_game(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $game = Game::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.games.destroy', $game));

        $response->assertRedirect(route('admin.panel', ['#productos']));
        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    /**
     * Category CRUD Tests
     */
    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Indie',
            'slug' => 'indie'
        ]);

        $response->assertRedirect(route('admin.panel', ['#categorias']));
        $this->assertDatabaseHas('categories', ['name' => 'Indie']);
    }

    /**
     * User CRUD Tests
     */
    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client'
        ]);

        $response->assertRedirect(route('admin.panel', ['#usuarios']));
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    /**
     * Order Tests
     */
    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->put(route('admin.orders.update', $order), [
            'status' => 'paid'
        ]);

        $response->assertRedirect(route('admin.panel', ['#pedidos']));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
    }
}
