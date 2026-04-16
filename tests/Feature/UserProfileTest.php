<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test usuario puede ver su perfil.
     */
    public function test_user_can_view_profile(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($user)->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    /**
     * Test usuario puede actualizar su perfil.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'role' => 'client',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'John Wayne',
            'country' => 'USA',
            'birth_date' => '1990-01-01',
        ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Wayne',
            'country' => 'USA',
        ]);
    }

    /**
     * Test usuario puede ver su historial de pedidos.
     */
    public function test_user_can_view_order_history(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        Order::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('profile.orders'));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }
}
