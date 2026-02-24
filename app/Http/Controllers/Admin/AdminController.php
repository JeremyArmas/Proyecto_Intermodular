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
     * Muestra el resumen y estadÃ­sticas del panel de administraciÃ³n.
     */
    public function index()
    {
        // Obtener colecciones dinÃ¡micas de la base de datos
        $productos = Game::with(['categories', 'platform'])->orderBy('updated_at', 'desc')->take(10)->get();
        $categorias = Category::withCount('games')->orderBy('updated_at', 'desc')->take(10)->get();
        $usuarios = User::orderBy('created_at', 'desc')->take(10)->get();
        $pedidos = Order::with('user')->orderBy('created_at', 'desc')->take(10)->get();

        // KPIs
        $totalProductos = Game::count();
        $bajoStock = Game::where('stock', '>', 0)->where('stock', '<=', 10)->count();
        $sinStock = Game::where('stock', 0)->count();
        $borradores = Game::where('is_active', false)->count();

        return view('adminPanel', compact(
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
