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
        $query = Game::where('is_active', true);

        // Filtros (opcionales por ahora, pero preparados)
        if ($request->has('platform')) {
            $query->whereHas('platform', function($q) use ($request) {
                $q->where('slug', $request->platform);
            });
        }

        $games = $query->with('platform')->paginate(12);

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
