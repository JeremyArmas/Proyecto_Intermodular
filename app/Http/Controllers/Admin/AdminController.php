<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\ContactMessage;

class AdminController extends Controller
{
    /**
     * Muestra el resumen y estadísticas del panel de administración.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $productosQuery = Game::with(['categories', 'platform']);

        // Búsqueda por texto
        if ($request->filled('search')) {
            $s = strtolower($request->search);
            $productosQuery->where(function ($q) use ($s) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$s}%"])
                  ->orWhereHas('categories', function($qCat) use ($s) {
                      $qCat->whereRaw('LOWER(name) LIKE ?', ["%{$s}%"]);
                  })
                  ->orWhereHas('platform', function($qPlat) use ($s) {
                      $qPlat->whereRaw('LOWER(name) LIKE ?', ["%{$s}%"]);
                  });
            });
        }

        // Filtro por Estado
        if ($request->filled('estado')) {
            $estado = strtolower($request->estado);
            if ($estado === 'publicado') {
                $productosQuery->where('is_active', true);
            } elseif ($estado === 'borrador') {
                $productosQuery->where('is_active', false);
            }
        }

        // Ordenamiento
        $orden = $request->input('orden', 'fecha_desc');
        switch ($orden) {
            case 'nombre_asc':
                $productosQuery->orderBy('title', 'asc');
                break;
            case 'nombre_desc':
                $productosQuery->orderBy('title', 'desc');
                break;
            case 'precio_asc':
                $productosQuery->orderBy('price', 'asc');
                break;
            case 'precio_desc':
                $productosQuery->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $productosQuery->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $productosQuery->orderBy('stock', 'desc');
                break;
            case 'fecha_asc':
                $productosQuery->orderBy('updated_at', 'asc');
                break;
            case 'fecha_desc':
            default:
                $productosQuery->orderBy('updated_at', 'desc');
                break;
        }

        $productos = $productosQuery->paginate(10, ['*'], 'productos_page')->withQueryString();
        
        $categorias = Category::withCount('games')->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'categorias_page')->withQueryString();
        $usuarios = User::orderBy('created_at', 'desc')->paginate(10, ['*'], 'usuarios_page')->withQueryString();
        $pedidos = Order::with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'pedidos_page')->withQueryString();
        
        $tickets = ContactMessage::orderBy('created_at', 'desc')->paginate(10, ['*'], 'tickets_page')->withQueryString();
        
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
