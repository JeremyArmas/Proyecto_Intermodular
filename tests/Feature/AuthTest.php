<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login con credenciales correctas devuelve éxito.
     */
    public function test_login_with_valid_credentials_succeeds(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'client',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login con contraseña incorrecta falla.
     */
    public function test_login_with_wrong_password_fails(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
        $this->assertGuest();
    }

    /**
     * Test login con email inexistente falla.
     */
    public function test_login_with_nonexistent_email_fails(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'noexiste@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
        $this->assertGuest();
    }

    /**
     * Test login sin email falla con validación.
     */
    public function test_login_requires_email(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test login sin contraseña falla con validación.
     */
    public function test_login_requires_password(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test login de admin redirige al panel de admin.
     */
    public function test_admin_login_redirects_to_admin_panel(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['redirect' => route('admin.panel')]);
    }

    /**
     * Test logout cierra la sesión correctamente.
     */
    public function test_logout_works_correctly(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }


    /**
     * Test la ruta GET /login redirige a home.
     */
    public function test_get_login_route_redirects_to_home(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect(route('home'));
    }
}
