<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que el middleware establece EUR por defecto.
     */
    public function test_middleware_sets_default_currency()
    {
        $this->get('/')
            ->assertSessionHas('currency', 'EUR');
    }

    /**
     * Test que se puede cambiar la moneda.
     */
    public function test_can_switch_currency()
    {
        // Cambiar a USD
        $this->get(route('currency.switch', 'USD'))
            ->assertRedirect()
            ->assertSessionHas('currency', 'USD');

        // Cambiar a GBP
        $this->get(route('currency.switch', 'GBP'))
            ->assertRedirect()
            ->assertSessionHas('currency', 'GBP');
    }

    /**
     * Test que no se puede cambiar a una moneda no soportada.
     */
    public function test_cannot_switch_to_unsupported_currency()
    {
        $this->get(route('currency.switch', 'JPY'))
            ->assertSessionHas('currency', 'EUR'); // Debería seguir en EUR (o el anterior)
    }

    /**
     * Test del formateo de precios en diferentes monedas.
     */
    public function test_currency_formatting()
    {
        session(['currency' => 'EUR']);
        $this->assertEquals('10,00 €', CurrencyService::format(10));

        session(['currency' => 'USD']);
        // 10 EUR * 1.08 = 10.80 USD
        $this->assertEquals('10,80 $', CurrencyService::format(10));

        session(['currency' => 'GBP']);
        // 10 EUR * 0.86 = 8.60 GBP
        $this->assertEquals('8,60 £', CurrencyService::format(10));
    }

    /**
     * Test de la conversión inversa (de moneda actual a EUR) para filtros.
     */
    public function test_reverse_conversion_to_eur()
    {
        session(['currency' => 'USD']);
        // 10.80 USD / 1.08 = 10.00 EUR
        $this->assertEquals(10, CurrencyService::convertToEur(10.80));

        session(['currency' => 'GBP']);
        // 8.60 GBP / 0.86 = 10.00 EUR
        $this->assertEquals(10, CurrencyService::convertToEur(8.60));
    }

    /**
     * Test que los filtros de precio en el catálogo funcionan con USD.
     */
    public function test_catalog_price_filter_with_usd()
    {
        // Crear un juego de 50€ (~54 USD con tasa 1.08)
        $game = Game::factory()->create(['price' => 50, 'is_active' => true]);
        
        // Entrar en modo USD
        $this->withSession(['currency' => 'USD']);

        // Filtrar por juegos de hasta 40 USD. No debería encontrar el juego de 50€ (54 USD)
        $response = $this->get('/catalogo?price_max=40');
        $response->assertDontSee($game->title);

        // Filtrar por juegos de hasta 60 USD. Debería encontrarlo.
        $response = $this->get('/catalogo?price_max=60');
        $response->assertSee($game->title);
    }

    /**
     * Test que los precios en las vistas se muestran convertidos.
     */
    public function test_views_display_converted_prices()
    {
        $game = Game::factory()->create(['price' => 100, 'is_active' => true]);

        // Ver en EUR
        $this->withSession(['currency' => 'EUR'])
            ->get(route('juego.show', $game->slug))
            ->assertSee('100,00 €');

        // Ver en USD (100 * 1.08 = 108.00)
        $this->withSession(['currency' => 'USD'])
            ->get(route('juego.show', $game->slug))
            ->assertSee('108,00 $');

        // Ver en GBP (100 * 0.86 = 86.00)
        $this->withSession(['currency' => 'GBP'])
            ->get(route('juego.show', $game->slug))
            ->assertSee('86,00 £');
    }
}
