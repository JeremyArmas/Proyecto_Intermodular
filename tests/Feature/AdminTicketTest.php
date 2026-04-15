<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\RespondTicketMail;

class AdminTicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin puede ver el listado de tickets.
     */
    public function test_admin_can_view_tickets_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ContactMessage::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.tickets.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tickets');
    }

    /**
     * Test admin puede ver el detalle de un ticket y esto lo bloquea.
     */
    public function test_admin_viewing_ticket_locks_it(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = ContactMessage::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.tickets.show', $ticket->id));

        $response->assertStatus(200);
        $this->assertDatabaseHas('contact_messages', [
            'id' => $ticket->id,
            'admin_id' => $admin->id,
        ]);
        $this->assertNotNull($ticket->fresh()->locked_at);
    }

    /**
     * Test otro admin no puede ver un ticket bloqueado recientemente.
     */
    public function test_another_admin_cannot_view_recently_locked_ticket(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);
        $ticket = ContactMessage::factory()->create([
            'admin_id' => $admin1->id,
            'locked_at' => now(),
        ]);

        $response = $this->actingAs($admin2)->get(route('admin.tickets.show', $ticket->id));

        $response->assertRedirect(route('admin.tickets.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test admin puede responder a un ticket.
     */
    public function test_admin_can_respond_to_ticket(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = ContactMessage::factory()->create(['status' => 'pendiente']);

        $response = $this->actingAs($admin)->put(route('admin.tickets.update', $ticket->id), [
            'respuesta_email' => 'Esta es una respuesta de prueba.',
        ]);

        $response->assertRedirect(route('admin.panel'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'id' => $ticket->id,
            'status' => 'finalizado',
            'respuesta_email' => 'Esta es una respuesta de prueba.',
        ]);

        Mail::assertSent(RespondTicketMail::class, function ($mail) use ($ticket) {
            return $mail->hasTo($ticket->email);
        });
    }
}
