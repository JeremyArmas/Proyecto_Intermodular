<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Administrator;
use App\Models\User;
use App\Models\Platform;
use App\Models\Category;
use App\Models\Game;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private \App\Models\Administrator $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Usar un admin existente (como diego2004) o crear uno en memoria
        $this->superAdmin = Administrator::firstOrCreate(
            ['email' => 'super_test_admin@test.com'],
            ['name' => 'Test Admin', 'password' => bcrypt('password'), 'is_super_admin' => true]
        );
    }

    protected function tearDown(): void
    {
        $this->superAdmin->delete();
        parent::tearDown();
    }

    public function test_can_view_all_indexes()
    {
        $this->actingAs($this->superAdmin, 'admin');

        $this->get('/admin/administrators')->assertStatus(200);
        $this->get('/admin/users')->assertStatus(200);
        $this->get('/admin/games')->assertStatus(200);
        $this->get('/admin/categories')->assertStatus(200);
        $this->get('/admin/platforms')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
    }

    public function test_can_create_update_delete_category()
    {
        $this->actingAs($this->superAdmin, 'admin');

        $response = $this->post('/admin/categories', [
            'name' => 'Categoría Test',
            'slug' => 'categoria-test',
            'description' => 'Test'
        ]);
        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Categoría Test']);

        $category = Category::where('name', 'Categoría Test')->first();

        $this->put('/admin/categories/'.$category->id, [
            'name' => 'Categoría Editada',
            'slug' => 'categoria-editada',
            'description' => 'Test Edit',
        ])->assertRedirect('/admin/categories');
        
        $this->assertDatabaseHas('categories', ['name' => 'Categoría Editada']);

        $this->delete('/admin/categories/'.$category->id)->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_can_create_update_delete_platform()
    {
        $this->actingAs($this->superAdmin, 'admin');

        $response = $this->post('/admin/platforms', [
            'name' => 'Plataforma Test',
            'slug' => 'plataforma-test',
            'manufacturer' => 'Test Corp'
        ]);
        $response->assertRedirect('/admin/platforms');
        $this->assertDatabaseHas('platforms', ['name' => 'Plataforma Test']);

        $platform = Platform::where('name', 'Plataforma Test')->first();

        $this->put('/admin/platforms/'.$platform->id, [
            'name' => 'Plataforma Editada',
            'slug' => 'plataforma-editada',
            'manufacturer' => 'Test Corp',
        ])->assertRedirect('/admin/platforms');
        
        $this->assertDatabaseHas('platforms', ['name' => 'Plataforma Editada']);

        $this->delete('/admin/platforms/'.$platform->id)->assertRedirect('/admin/platforms');
        $this->assertDatabaseMissing('platforms', ['id' => $platform->id]);
    }

    public function test_can_create_update_delete_game()
    {
        $this->actingAs($this->superAdmin, 'admin');
        
        $platform = Platform::firstOrCreate(['name' => 'Temp Plat'], ['slug' => 'temp-plat', 'manufacturer' => 'M']);
        $category = Category::firstOrCreate(['name' => 'Temp Cat'], ['slug' => 'temp-cat', 'description' => 'D']);

        $this->post('/admin/games', [
            'title' => 'Juego Test O',
            'slug' => 'juego-test-o',
            'price' => 59.99,
            'b2b_price' => 39.99,
            'stock' => 10,
            'developer' => 'Dev',
            'platform_id' => $platform->id,
            'categories' => [$category->id], // ManyToMany
            'is_active' => "1",
        ])->assertRedirect('/admin/games');
        
        $this->assertDatabaseHas('games', ['title' => 'Juego Test O']);

        $game = Game::where('title', 'Juego Test O')->first();

        // Check if categories synced
        $this->assertTrue($game->categories->contains($category));

        // Delete Game
        $this->delete('/admin/games/'.$game->id)->assertRedirect('/admin/games');
        $this->assertDatabaseMissing('games', ['id' => $game->id]);
        
        $platform->delete();
        $category->delete();
    }
}
