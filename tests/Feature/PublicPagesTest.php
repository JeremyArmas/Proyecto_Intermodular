<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_sobre_nosotros_page_is_accessible(): void
    {
        $response = $this->get('/sobre-nosotros');
        $response->assertStatus(200);
    }

    public function test_noticias_page_is_accessible(): void
    {
        $response = $this->get('/noticias');
        $response->assertStatus(200);
    }

    public function test_soporte_page_is_accessible(): void
    {
        $response = $this->get('/soporte');
        $response->assertStatus(200);
    }

    public function test_faq_page_is_accessible(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
    }

    public function test_contacto_page_is_accessible(): void
    {
        $response = $this->get('/contacto');
        $response->assertStatus(200);
    }

    public function test_login_page_redirects_to_home_with_message(): void
    {
        $response = $this->get('/login');
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Debes iniciar sesión para acceder.');
    }
}
