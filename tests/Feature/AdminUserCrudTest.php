<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Administrator;

class AdminUserCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de usuarios.
     */
    public function test_admin_can_view_users_index(): void
    {
        $this->loginAsAdmin();
        User::factory()->count(3)->create(['role' => 'client']);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede ver el formulario de crear usuario.
     */
    public function test_admin_can_view_create_user_form(): void
    {
        $this->loginAsAdmin();

        $response = $this->get(route('admin.users.create'));

        $response->assertStatus(200);
    }

    /**
     * Test admin puede actualizar un usuario.
     */
    public function test_admin_can_update_user(): void
    {
        $this->loginAsAdmin();
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'role' => 'client',
        ]);

        $response = $this->put(route('admin.users.update', $user->id), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'client',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test admin puede cambiar el rol de un usuario.
     */
    public function test_admin_can_change_user_role(): void
    {
        $this->loginAsAdmin();
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->put(route('admin.users.update', $user->id), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'company',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'company']);
    }

    /**
     * Test admin puede eliminar un usuario.
     */
    public function test_admin_can_delete_user(): void
    {
        $this->loginAsAdmin();
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->delete(route('admin.users.destroy', $user->id));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Test crear usuario sin email falla con error de validación.
     */
    public function test_create_user_requires_email(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test crear usuario con email duplicado falla.
     */
    public function test_create_user_fails_with_duplicate_email(): void
    {
        $this->loginAsAdmin();
        User::factory()->create(['email' => 'existing@example.com', 'role' => 'client']);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test crear usuario con contraseña corta falla.
     */
    public function test_create_user_fails_with_short_password(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => '123',
            'password_confirmation' => '123',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test crear usuario con contraseñas que no coinciden falla.
     */
    public function test_create_user_fails_with_password_mismatch(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different456',
            'role' => 'client',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test crear usuario con rol inválido falla.
     */
    public function test_create_user_fails_with_invalid_role(): void
    {
        $this->loginAsAdmin();

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'superadmin',
        ]);

        $response->assertSessionHasErrors('role');
    }

    /**
     * Test un cliente no puede acceder al CRUD de usuarios.
     */
    public function test_client_cannot_access_users_crud(): void
    {
        $client = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($client)->get(route('admin.users.index'));

        $response->assertRedirect('/login');
    }
}
