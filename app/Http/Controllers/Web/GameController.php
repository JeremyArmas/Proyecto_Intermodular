<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Platform;
use App\Models\Category;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Muestra el catálogo de juegos desde la base de datos.
     */
    public function index(Request $request)
    {
<<<<<<< HEAD
        // Colecciones para los desplegables de filtros
        $platforms = Platform::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        // Inicia la consulta para obtener juegos activos
        $query = Game::where('is_active', true);

        // Búsqueda por título
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Búsqueda por plataforma
        if ($request->filled('platform')) {
            $platformSearch = strtolower($request->platform);
            $query->whereHas('platform', function($q) use ($platformSearch) {
                if ($platformSearch === 'playstation') {
                    $q->where('slug', 'like', 'ps%')->orWhere('slug', 'like', '%playstation%');
                } elseif ($platformSearch === 'xbox') {
                    $q->where('slug', 'like', '%xbox%');
                } else {
                    $q->where('slug', $platformSearch);
                }
            });
        }

        // Búsqueda por categoría (slug)
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.slug', $request->category);
            });
        }

        // Búsqueda por estado
        if ($request->filled('status')) {
            if ($request->status === 'instock') {
                $query->where(function($q) {
                    $q->whereNull('release_date')->orWhereDate('release_date', '<=', now());
                })->where('stock', '>', 0);
            } elseif ($request->status === 'outofstock') {
                $query->where(function($q) {
                    $q->whereNull('release_date')->orWhereDate('release_date', '<=', now());
                })->where('stock', '<=', 0);
            } elseif ($request->status === 'upcoming') {
                $query->whereNotNull('release_date')->whereDate('release_date', '>', now());
            }
        }

        // Rango de precio (min y max)
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Ordenamiento
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('id', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $games = $query->with('platform')->paginate(12)->withQueryString();
=======
        $query = Game::where('is_active', true);

        // Filtros (opcionales por ahora, pero preparados)
        if ($request->has('platform')) {
            $query->whereHas('platform', function($q) use ($request) {
                $q->where('slug', $request->platform);
            });
        }

        $games = $query->with('platform')->paginate(12);
>>>>>>> remotes/origin/rama-gabri-dev

        return view('catalogo', compact('games', 'platforms', 'categories'));
    }

    /**
     * Muestra la ficha técnica de un juego.
     */
    public function show($slug)
    {
        $game = Game::where('slug', $slug)->with(['platform', 'categories'])->firstOrFail();

        return view('juego', compact('game'));
    }
}
