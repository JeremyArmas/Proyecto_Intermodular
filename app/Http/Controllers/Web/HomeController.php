<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Convierte un modelo Game al formato de array que usa la vista
        $mapGame = fn($game) => [
            'title' => $game->title,
            'desc' => Str::limit($game->description, 70),
            'tag' => $game->platform->name ?? 'Multi',
            'slug' => $game->slug,
            'cover_image' => $game->cover_image,
            'youtube_id' => $game->youtube_id, // null si no tiene trailer
        ];

        // Próximamente: juegos que NO han salido todavía
        $upcoming = Game::with(['platform'])
            ->where('is_active', true)
            ->whereNotNull('release_date')
            ->whereDate('release_date', '>', now())
            ->orderBy('release_date', 'asc')
            ->take(3)
            ->get()
            ->map($mapGame)
            ->values()
            ->toArray();

        // Populares (Ejemplo: ordenados por stock o id decendente)
        $popular = Game::with(['platform'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('release_date')->orWhereDate('release_date', '<=', now());
            })
            ->orderBy('stock', 'asc')
            ->take(3)
            ->get()
            ->map($mapGame)
            ->values()
            ->toArray();

        // Gratis: juegos con precio 0 (el admin los pone a 0 desde el panel)
        $free = Game::with(['platform'])
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('release_date')->orWhereDate('release_date', '<=', now());
            })
            ->where('price', 0)
            ->take(3)
            ->get()
            ->map($mapGame)
            ->values()
            ->toArray();

        // Fallback: si alguna sección quedara vacía, usa la primera disponible
        if (empty($upcoming)) {
            $upcoming = [['title'=>'Próximamente','desc'=>'','tag'=>'','slug'=>'#','cover_image'=>null,'youtube_id'=>null]];
        }
        if (empty($popular)) $popular = $upcoming;
        if (empty($free)) $free = []; // Sección oculta si no hay juegos gratis aún

        // Slides del hero — apuntan al primer juego de cada sección
        $heroSlides = [
            [
                'pill' => 'Próximamente',
                'desc2' => 'Descubre lo que viene en camino y guarda tus favoritos.',
                'badgeClass' => 'badge-soft',
                'badgeText' => 'Soon',
                'game' => $upcoming[0],
                'youtube_id' => $upcoming[0]['youtube_id'] ?? null,
                'primary' => ['text' => 'Ver ficha',  'href' => url('/juego/' . $upcoming[0]['slug']),     'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos',  'href' => url('/catalogo?status=upcoming'),           'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo',   'href' => url('/catalogo'),                            'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Más populares',
                'desc2' => 'Lo más jugado ahora mismo. Entra a la ficha o mira el top completo.',
                'badgeClass' => 'badge-sun',
                'badgeText' => 'Top',
                'game' => $popular[0],
                'youtube_id' => $popular[0]['youtube_id'] ?? null,
                'primary' => ['text' => 'Ver ficha',  'href' => url('/juego/' . $popular[0]['slug']),       'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos',  'href' => url('/catalogo?sort=popular'),               'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo',   'href' => url('/catalogo'),                            'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Gratis',
                'desc2' => 'Entra sin pagar: juegos y packs para empezar rápido.',
                'badgeClass' => 'badge-mint',
                'badgeText' => 'Free',
                'game' => $free[0] ?? $upcoming[0],
                'youtube_id' => ($free[0]['youtube_id'] ?? $upcoming[0]['youtube_id']) ?? null,
                'primary' => ['text' => 'Ver ficha',  'href' => url('/juego/' . ($free[0]['slug'] ?? $upcoming[0]['slug'])), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos',  'href' => url('/catalogo?price_max=0'),               'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo',   'href' => url('/catalogo'),                            'class' => 'jg-btn-outline'],
            ],
        ];

        return view('home', compact('upcoming', 'popular', 'free', 'heroSlides'));
    }
}
