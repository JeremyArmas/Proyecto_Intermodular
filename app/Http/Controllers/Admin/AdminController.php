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
     * Muestra el resumen y estadísticas del panel de administración.
     */
    public function index()
    {
        
        // Obtiene 10 productos, categorías, usuarios y pedidos para mostrar en el panel
        $productos = Game::with(['categories', 'platform'])->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'productos_page')->withQueryString();
        $categorias = Category::withCount('games')->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'categorias_page')->withQueryString();
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10, ['*'], 'usuarios_page')->withQueryString();
        $tickets = \App\Models\ContactMessage::orderBy('created_at', 'asc')->paginate(10, ['*'], 'tickets_page')->withQueryString();
        $pedidos = Order::with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'pedidos_page')->withQueryString();
        
        // Estadísticas generales para mostrar en el panel
        $totalProductos = Game::count();
        $bajoStock = Game::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $sinStock = Game::where('stock', 0)->count();
        $borradores = Game::where('is_active', false)->count();

        // Retorna la vista del panel de administración con los datos obtenidos
        return view('/adminPanel', compact(
            'productos',
            'categorias',
            'usuarios',
            'pedidos',
            'tickets',
            'totalProductos',
            'bajoStock',
            'sinStock',
            'borradores'
        ));
    }
}
