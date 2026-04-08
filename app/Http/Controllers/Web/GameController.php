<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Muestra el catálogo de juegos desde la base de datos.
     */
    public function index(Request $request)
    {
        // Inicia la consulta para obtener juegos activos
        $query = Game::where('is_active', true);

        // Filtros de Plataforma
        if ($request->has('platform')) {
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

        // Filtro de precio
        if ($request->has('price') && $request->price === 'free') {
            $query->where('price', 0);
        }

        // Filtro de estado (próximos lanzamientos)
        if ($request->has('status') && $request->status === 'upcoming') {
            $query->where('stock', 0);
        }

        // Ordenamiento
        if ($request->has('sort')) {
            if ($request->sort === 'latest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort === 'popular') {
                
                // Ordenar por ID desc como fallback para más populares
                $query->orderBy('id', 'desc');
            }
        } else {
            
            // Orden por defecto
            $query->orderBy('created_at', 'desc');
        }

        $games = $query->with('platform')->paginate(12)->withQueryString();

        return view('catalogo', compact('games'));
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
