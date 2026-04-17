<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_login_route_redirects_to_home(): void
    {
        $response = $this->get('/login');
        $response->assertRedirect('/');
    }
}
