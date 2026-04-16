<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Administrator;
use App\Models\Platform;

class AdminPlatformCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de plataformas.
     */
    public function test_admin_can_view_platforms_index(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        Platform::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.platforms.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede ver el formulario de crear plataforma.
     */
    public function test_admin_can_view_create_platform_form(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.platforms.create'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede crear una plataforma.
     */
    public function test_admin_can_create_platform(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.platforms.store'), [
            'name' => 'PlayStation 5',
            'slug' => 'playstation-5',
        ]);

        $response->assertRedirect(route('admin.platforms.index'));
        $this->assertDatabaseHas('platforms', ['name' => 'PlayStation 5', 'slug' => 'playstation-5']);
    }

    /**
     * Test admin puede actualizar una plataforma.
     */
    public function test_admin_can_update_platform(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        $platform = Platform::factory()->create(['name' => 'PS4', 'slug' => 'ps4']);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.platforms.update', $platform), [
            'name' => 'PlayStation 4',
            'slug' => 'playstation-4',
        ]);

        $response->assertRedirect(route('admin.platforms.index'));
        $this->assertDatabaseHas('platforms', ['name' => 'PlayStation 4', 'slug' => 'playstation-4']);
        $this->assertDatabaseMissing('platforms', ['name' => 'PS4']);
    }

    /**
     * Test admin puede eliminar una plataforma.
     */
    public function test_admin_can_delete_platform(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        $platform = Platform::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.platforms.destroy', $platform));

        $response->assertRedirect(route('admin.platforms.index'));
        $this->assertDatabaseMissing('platforms', ['id' => $platform->id]);
    }

    /**
     * Test crear plataforma sin nombre falla.
     */
    public function test_create_platform_requires_name(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.platforms.store'), [
            'name' => '',
            'slug' => 'test-slug',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test crear plataforma con slug duplicado falla.
     */
    public function test_create_platform_fails_with_duplicate_slug(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        Platform::factory()->create(['slug' => 'pc']);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.platforms.store'), [
            'name' => 'PC Duplicado',
            'slug' => 'pc',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    /**
     * Test un cliente no puede acceder al CRUD de plataformas.
     */
    public function test_client_cannot_access_platforms_crud(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('admin.platforms.index'));

        $response->assertRedirect('/login');
    }
}
