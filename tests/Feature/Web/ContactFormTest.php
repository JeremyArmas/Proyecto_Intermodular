<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mews\Captcha\Facades\Captcha;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica que si los campos están vacíos, salta la validación obligatoria.
     */
    public function test_contact_form_requires_all_fields(): void
    {
        $response = $this->post(route('contacto.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message', 'captcha']);
    }

    /**
     * Verifica que con un captcha inválido la petición falla pero no borra la BDD.
     */
    public function test_contact_form_fails_with_invalid_captcha(): void
    {
        // Forzamos que la validación del Captcha siempre falle internamente simulándolo
        Captcha::shouldReceive('check')->once()->andReturn(false);

        $response = $this->post(route('contacto.store'), [
            'name' => 'Diego',
            'email' => 'diego@ejemplo.com',
            'subject' => 'Ayuda urgente',
            'message' => 'No puedo pagar mi pedido de test.',
            'captcha' => 'wrong_captcha_123',
        ]);

        $response->assertSessionHasErrors('captcha');
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'diego@ejemplo.com'
        ]);
    }

    /**
     * Verifica la subida con datos correctos guardándose en base de datos.
     */
    public function test_contact_form_success_stores_in_database(): void
    {
        // Forzamos que la validación de Captcha diga "OK, es válido!"
        Captcha::shouldReceive('check')->once()->with('valid_captcha')->andReturn(true);

        $response = $this->from(route('contacto'))->post(route('contacto.store'), [
            'name' => 'Carlos',
            'email' => 'carlos@prueba.com',
            'subject' => 'Consulta técnica',
            'message' => 'Mi juego no arranca en Windows 11.',
            'captcha' => 'valid_captcha', // La fachada pasará este valor como "true"
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
