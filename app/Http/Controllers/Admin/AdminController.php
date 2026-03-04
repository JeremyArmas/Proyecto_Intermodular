<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    /**
     * Muestra el resumen y estadÃ­sticas del panel de administración.
     */
    public function index()
    {
        // Si prefieres que la ruta principal del panel apunte a este
        // método, podrías dejar aquí las consultas y devolver la vista con
        // datos reales. Hoy en día redireccionamos desde la ruta a un CRUD,
        // así que este controlador puede quedar vacío o eliminarse.
        
        // Ejemplo de cómo cargar estadísticas reales (opcional):
        
        $productos = Game::with(['categories', 'platform'])
                         ->orderBy('updated_at', 'desc')->take(10)->get();
        $categorias = Category::withCount('games')
                              ->orderBy('updated_at', 'desc')->take(10)->get();
        $usuarios = User::orderBy('created_at', 'desc')->take(10)->get();
        $pedidos = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        $totalProductos = Game::count();
        $bajoStock      = Game::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $sinStock       = Game::where('stock', 0)->count();
        $borradores     = Game::where('is_active', false)->count();

        return view('/adminPanel', compact(
            'productos',
            'categorias',
            'usuarios',
            'pedidos',
            'totalProductos',
            'bajoStock',
            'sinStock',
            'borradores'
        ));
    }
}

