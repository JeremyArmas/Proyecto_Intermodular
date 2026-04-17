<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrator;
use App\Models\User;

class AdminAdministratorCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test super admin puede ver el listado de administradores.
     */
    public function test_super_admin_can_view_administrators_index(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);
        Administrator::factory()->count(2)->create(['is_super_admin' => false]);

        $response = $this->actingAs($superAdmin, 'admin')->get(route('admin.administrators.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin normal NO PUEDE ver el listado de administradores (403 Forbidden o Redirect).
     * Nota: asumo que la comprobación está en la política o middleware.
     */
    public function test_normal_admin_cannot_view_administrators_index(): void
    {
        $normalAdmin = Administrator::factory()->create(['is_super_admin' => false]);

        $response = $this->actingAs($normalAdmin, 'admin')->get(route('admin.administrators.index'));

        // Ya sea que dé 403 o redirija afuera...
        $response->assertStatus(403);
    }

    /**
     * Test super admin puede ver formulario crear administrador.
     */
    public function test_super_admin_can_view_create_administrator_form(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($superAdmin, 'admin')->get(route('admin.administrators.create'));

        $response->assertStatus(200);
    }

    /**
     * Test super admin puede crear un administrador.
     */
    public function test_super_admin_can_create_administrator(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($superAdmin, 'admin')->post(route('admin.administrators.store'), [
            'name' => 'Nuevo Admin',
            'email' => 'nuevo@admin.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_super_admin' => 0,
        ]);

        $response->assertRedirect(route('admin.administrators.index'));
        $this->assertDatabaseHas('administrators', [
            'name' => 'Nuevo Admin',
            'email' => 'nuevo@admin.com',
            'is_super_admin' => 0,
        ]);
    }

    /**
     * Test super admin puede editar un administrador (nombre y email).
     */
    public function test_super_admin_can_edit_administrator(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);
        $targetAdmin = Administrator::factory()->create(['name' => 'Old Name', 'email' => 'old@admin.com']);

        $response = $this->actingAs($superAdmin, 'admin')->put(route('admin.administrators.update', $targetAdmin->id), [
            'name' => 'Updated Admin',
            'email' => 'updated@admin.com',
            'is_super_admin' => 0,
        ]);

        $response->assertRedirect(route('admin.administrators.index'));
        $this->assertDatabaseHas('administrators', [
            'id' => $targetAdmin->id,
            'name' => 'Updated Admin',
            'email' => 'updated@admin.com',
        ]);
    }

    /**
     * Test super admin puede eliminar a otro administrador.
     */
    public function test_super_admin_can_delete_another_administrator(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);
        $targetAdmin = Administrator::factory()->create();

        $response = $this->actingAs($superAdmin, 'admin')->delete(route('admin.administrators.destroy', $targetAdmin->id));

        $response->assertRedirect(route('admin.administrators.index'));
        $this->assertDatabaseMissing('administrators', ['id' => $targetAdmin->id]);
    }

    /**
     * Test validación falla cuando se crea admin sin email.
     */
    public function test_create_administrator_requires_email(): void
    {
        $superAdmin = Administrator::factory()->create(['is_super_admin' => true]);

        $response = $this->actingAs($superAdmin, 'admin')->post(route('admin.administrators.store'), [
            'name' => 'Test Name',
            'email' => '',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
            'is_super_admin' => 0,
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test administrador normal no puede eliminar a nadie.
     */
    public function test_normal_admin_cannot_delete_administrators(): void
    {
        $normalAdmin = Administrator::factory()->create(['is_super_admin' => false]);
        $targetAdmin = Administrator::factory()->create();

        $response = $this->actingAs($normalAdmin, 'admin')->delete(route('admin.administrators.destroy', $targetAdmin->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('administrators', ['id' => $targetAdmin->id]);
    }

    /**
     * Test cliente no puede acceder a administradores.
     */
    public function test_client_cannot_access_administrators_crud(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('admin.administrators.index'));

        // Clientes autenticados en el guard 'web' van a ser redirigidos.
        $response->assertRedirect('/login');
    }
}
