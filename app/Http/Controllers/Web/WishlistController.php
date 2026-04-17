<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Administrator;

class WishlistController extends Controller
{
    /**
     * Muestra la lista de deseos del usuario.
     */
    /**
     * Muestra la lista de deseos del usuario.
     */
    public function index()
    {
        $user = $this->resolveUser();
        $isAdmin = $user instanceof Administrator;
        $column = $isAdmin ? 'administrator_id' : 'user_id';
        
        // Al entrar en la lista, marcamos todos los elementos del usuario como "leídos"
        // para que desaparezca la notificación del navbar.
        Wishlist::where($column, $user->id)->update(['is_read' => true]);

        // Obtener juegos en la wishlist con paginación
        $wishlistGames = Wishlist::where($column, $user->id)
            ->with(['game' => function($query) {
                $query->with(['platform', 'categories'])->where('is_active', true);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Extraer categorías de su lista (Top 6 por frecuencia) para el sidebar inteligente
        $userCategories = Wishlist::where($column, $user->id)
            ->with('game.categories')
            ->get()
            ->pluck('game.categories')
            ->flatten()
            ->filter() // Filtra nulos si algún juego no tiene categorías
            ->groupBy('id')
            ->map(function ($items) {
                $category = $items->first();
                $category->count = $items->count();
                return $category;
            })
            ->sortByDesc('count')
            ->take(6);

        return view('wishlist', compact('wishlistGames', 'userCategories'));
    }

    /**
     * Agrega un juego a la lista de deseos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
        ]);

        $user = $this->resolveUser();
        $isAdmin = $user instanceof Administrator;
        $column = $isAdmin ? 'administrator_id' : 'user_id';
        
        $game = Game::findOrFail($request->game_id);

        // Lógica de Toggle: Si existe, lo borramos. Si no existe, lo creamos.
        $wishlistItem = Wishlist::where($column, $user->id)
            ->where('game_id', $game->id)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $status = 'removed';
            $message = 'Juego eliminado de tu lista de deseos.';
        } else {
            // Verifica si el juego está activo antes de añadirlo
            if (!$game->is_active) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'El juego no está disponible.'], 400);
                }
                return back()->with('error', 'El juego no está disponible.');
            }

            Wishlist::create([
                $column => $user->id,
                'game_id' => $game->id,
                'is_read' => false, // Es nuevo, así que aparecerá en el contador
            ]);
            $status = 'added';
            $message = '¡Juego añadido a tu lista de deseos!';
        }

        // Contar cuántos juegos NO leídos tiene ahora el usuario para el Navbar
        $newCount = Wishlist::where($column, $user->id)->where('is_read', false)->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message,
                'new_count' => $newCount
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Elimina un juego de la lista de deseos.
     */
    public function destroy(Wishlist $wishlist)
    {
        $user = $this->resolveUser();
        $isAdmin = $user instanceof Administrator;
        $authorized = $isAdmin ? ($wishlist->administrator_id === $user->id) : ($wishlist->user_id === $user->id);

        // Verificar que el usuario sea el dueño de la wishlist
        if (!$authorized) {
            abort(403, 'No tienes permiso para eliminar este juego de la lista de deseos.');
        }

        $wishlist->delete();

        return back()->with('success', 'Juego eliminado de tu lista de deseos.');
    }

    /**
     * Mueve un juego de la lista de deseos al carrito (Solo para Clientes).
     */
    public function moveToCart(Wishlist $wishlist)
    {
        $user = $this->resolveUser();
        
        // Los administradores no tienen carrito en esta implementación
        if ($user instanceof Administrator) {
            return back()->with('error', 'Los administradores no pueden realizar compras ni mover items al carrito.');
        }

        // Verificar que el usuario sea el dueño de la wishlist
        if ($wishlist->user_id !== $user->id) {
            abort(403, 'No tienes permiso para mover este juego al carrito.');
        }

        $game = $wishlist->game;

        // Verificar si el juego está activo y en stock
        if (!$game->is_active || $game->stock <= 0) {
            return back()->with('error', 'No se puede mover el juego al carrito porque no está disponible.');
        }

        // Agregar al carrito (DATABASE DRIVEN)
        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
        $cartItem = $userCart->items()->where('game_id', $game->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $userCart->items()->create([
                'game_id' => $game->id,
                'quantity' => 1,
            ]);
        }

        // Eliminar de la wishlist
        $wishlist->delete();

        return redirect()->route('carrito.index')
            ->with('success', '¡El juego ha sido movido al carrito!');
    }

    /**
     * Resuelve el usuario autenticado ya sea por el guard 'web' o 'admin'.
     */
    private function resolveUser()
    {
        return auth()->guard('web')->user() ?? auth()->guard('admin')->user();
    }
}