<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Administrator;
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
        $this->loginAsAdmin();
        ContactMessage::factory()->count(3)->create();

        $response = $this->get(route('admin.tickets.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tickets');
    }

    /**
     * Test admin puede ver el detalle de un ticket y esto lo bloquea.
     */
    public function test_admin_viewing_ticket_locks_it(): void
    {
        $admin = $this->loginAsAdmin();
        $ticket = ContactMessage::factory()->create();

        $response = $this->get(route('admin.tickets.show', $ticket->id));

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
        $admin1 = Administrator::factory()->create();
        $admin2 = $this->loginAsAdmin();
        $ticket = ContactMessage::factory()->create([
            'admin_id' => $admin1->id,
            'locked_at' => now(),
        ]);

        $response = $this->get(route('admin.tickets.show', $ticket->id));

        $response->assertRedirect(route('admin.tickets.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test admin puede responder a un ticket.
     */
    public function test_admin_can_respond_to_ticket(): void
    {
        Mail::fake();

        $this->loginAsAdmin();
        $ticket = ContactMessage::factory()->create(['status' => 'pendiente']);

        $response = $this->put(route('admin.tickets.update', $ticket->id), [
            'respuesta_email' => 'Esta es una respuesta de prueba.',
        ]);

        $response->assertRedirect(route('admin.panel'));
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
