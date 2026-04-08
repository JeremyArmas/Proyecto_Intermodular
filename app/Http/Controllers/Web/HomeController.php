<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Carga todos los juegos activos con sus relaciones
        $allGames = Game::with(['platform', 'categories'])
            ->where('is_active', true)
            ->get();

        // Convierte un modelo Game al formato de array que usa la vista
        $mapGame = fn($game) => [
            'title' => $game->title,
            'desc' => Str::limit($game->description, 70),
            'tag' => $game->platform->name ?? 'Multi',
            'slug' => $game->slug,
            'cover_image' => $game->cover_image,
        ];

        // Secciones del home: repartimos los juegos de la BD en tres grupos
        // En el futuro se pueden filtrar por campos reales (status, price, featured…)
        $upcoming = $allGames->take(3)->map($mapGame)->values()->toArray();
        $popular  = $allGames->slice(3, 3)->map($mapGame)->values()->toArray();

        // Gratis: juegos con precio 0 (el admin los pone a 0 desde el panel)
        $free = Game::with(['platform'])
            ->where('is_active', true)
            ->where('price', 0)
            ->take(3)
            ->get()
            ->map($mapGame)
            ->values()
            ->toArray();

        // Fallback: si alguna sección quedara vacía, usa la primera disponible
        if (empty($upcoming)) $upcoming = [['title'=>'Próximamente','desc'=>'','tag'=>'','slug'=>'#']];
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
                'mediaType' => 'video',
                'mediaSrc' => 'videos/Magrunner-Dark-Pulse-trailer.mp4',
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . $upcoming[0]['slug']), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Más populares',
                'desc2' => 'Lo más jugado ahora mismo. Entra a la ficha o mira el top completo.',
                'badgeClass' => 'badge-sun',
                'badgeText' => 'Top',
                'game' => $popular[0],
                'mediaType' => 'video',
                'mediaSrc' => 'videos/Oddworld-Soulstorm-trailer.mp4',
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . $popular[0]['slug']), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
            [
                'pill' => 'Gratis',
                'desc2' => 'Entra sin pagar: juegos y packs para empezar rápido.',
                'badgeClass' => 'badge-mint',
                'badgeText' => 'Free',
                'game' => $free[0] ?? $upcoming[0],
                'mediaType' => 'video',
                'mediaSrc' => 'videos/Hypercharge-Unboxed-trailer.mp4',
                'primary' => ['text' => 'Ver ficha', 'href' => url('/juego/' . ($free[0]['slug'] ?? $upcoming[0]['slug'])), 'class' => 'jg-btn-sun'],
                'secondary' => ['text' => 'Ver todos', 'href' => url('/catalogo?price=free'), 'class' => 'jg-btn-primary'],
                'tertiary' => ['text' => 'Catálogo', 'href' => url('/catalogo'), 'class' => 'jg-btn-outline'],
            ],
        ];

        return view('home', compact('upcoming', 'popular', 'free', 'heroSlides'));
    }
}
