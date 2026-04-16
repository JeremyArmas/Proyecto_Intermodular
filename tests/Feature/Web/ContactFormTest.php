<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que si los campos están vacíos, salta la validación obligatoria.
     */
    public function test_contact_form_requires_all_fields(): void
    {
        $response = $this->post(route('contacto.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    /**
     * Verifica la subida con datos correctos guardándose en base de datos.
     */
    public function test_contact_form_success_stores_in_database(): void
    {
        $response = $this->from(route('contacto'))->post(route('contacto.store'), [
            'name' => 'Carlos',
            'email' => 'carlos@prueba.com',
            'subject' => 'Consulta técnica',
            'message' => 'Mi juego no arranca en Windows 11.',
        ]);

        $response->assertRedirect(route('contacto'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Carlos',
            'email' => 'carlos@prueba.com',
            'status' => 'pendiente' // El default
        ]);
    }
}
