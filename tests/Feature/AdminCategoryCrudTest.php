<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Administrator;
use App\Models\Category;

class AdminCategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de categorías.
     */
    public function test_admin_can_view_categories_index(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        Category::factory()->count(3)->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede ver el formulario de crear categoría.
     */
    public function test_admin_can_view_create_category_form(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.categories.create'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede actualizar una categoría.
     */
    public function test_admin_can_update_category(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        $category = Category::factory()->create(['name' => 'Acción', 'slug' => 'accion']);

        $response = $this->actingAs($admin, 'admin')->put(route('admin.categories.update', $category), [
            'name' => 'Aventura',
            'slug' => 'aventura',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Aventura', 'slug' => 'aventura']);
        $this->assertDatabaseMissing('categories', ['name' => 'Acción']);
    }

    /**
     * Test admin puede eliminar una categoría.
     */
    public function test_admin_can_delete_category(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * Test crear categoría sin nombre falla con error de validación.
     */
    public function test_create_category_requires_name(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
            'name' => '',
            'slug' => 'test-slug',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test crear categoría sin slug falla con error de validación.
     */
    public function test_create_category_requires_slug(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
            'name' => 'Test Category',
            'slug' => '',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    /**
     * Test no se puede crear categoría con slug duplicado.
     */
    public function test_create_category_fails_with_duplicate_slug(): void
    {
        $admin = Administrator::factory()->create(['is_super_admin' => true]);
        Category::factory()->create(['slug' => 'accion']);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
            'name' => 'Acción Duplicada',
            'slug' => 'accion',
        ]);

        $response->assertSessionHasErrors('slug');
    }

    /**
     * Test un cliente no puede crear categorías.
     */
    public function test_client_cannot_create_category(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->post(route('admin.categories.store'), [
            'name' => 'Hack',
            'slug' => 'hack',
        ]);

        $response->assertRedirect('/login');
    }
}
