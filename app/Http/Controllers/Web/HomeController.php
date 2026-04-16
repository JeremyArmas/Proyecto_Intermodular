<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Función auxiliar para mapear juegos al formato de la vista
        $mapGame = fn($game) => [
            'id' => $game->id,
            'title' => $game->title,
            'desc' => Str::limit($game->description, 70),
            'tag' => $game->platform->name ?? 'Multi',
            'slug' => $game->slug,
            'cover_image' => $game->cover_image,
            'youtube_id' => $game->youtube_id ?? null,
        ];

        // Secciones de Jere: Próximamente, Populares, Gratis
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

        // Fallback para evitar errores si las secciones están vacías
        if (empty($upcoming)) $upcoming = [['title'=>'Próximamente','desc'=>'','tag'=>'','slug'=>'#']];
        if (empty($popular))  $popular  = $upcoming;
        if (empty($free))     $free     = []; 

        // Slides del Hero - Lógica de Jere con trailers de YouTube
        $heroSlides = [
            [
                'pill' => 'Próximamente',
                'desc2' => 'Descubre lo que viene en camino y guarda tus favoritos.',
                'badgeClass' => 'badge-soft',
                'badgeText' => 'Soon',
                'game' => $upcoming[0],
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . ($upcoming[0]['slug'] ?? '#')), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo?status=upcoming'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Más populares',
                'desc2' => 'Lo más jugado ahora mismo. Entra a la ficha o mira el top completo.',
                'badgeClass' => 'badge-sun',
                'badgeText' => 'Top',
                'game' => $popular[0],
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . ($popular[0]['slug'] ?? '#')), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo?sort=popular'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Gratis',
                'desc2' => 'Entra sin pagar: juegos y packs para empezar rápido.',
                'badgeClass' => 'badge-mint',
                'badgeText' => 'Free',
                'game' => !empty($free) ? $free[0] : $upcoming[0],
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . (!empty($free) ? $free[0]['slug'] : $upcoming[0]['slug'])), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo?price_max=0'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
        ];

        return view('home', compact('upcoming', 'popular', 'free', 'heroSlides'));
    }
}
