<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Muestra la página completa del carrito.
     */
    public function index()
    {
        $cart = $this->getCart();
        $items = $cart ? $cart->items()->with('game')->get() : collect();
        
        return view('carrito', compact('cart', 'items'));
    }

    /**
     * Añade un juego al carrito con validación de stock.
     */
    public function add(Request $request)
    {
        \Log::info('Petición para añadir al carrito:', $request->all());

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'quantity' => 'integer|min:1'
        ]);

        $game = Game::findOrFail($request->game_id);
        $quantityToAdd = $request->input('quantity', 1);

        // 1. Obtener o crear el carrito
        $cart = $this->getOrCreateCart();

        // 2. Buscar si el item ya existe en el carrito
        $cartItem = $cart->items()->where('game_id', $game->id)->first();
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;
        $totalRequested = $currentQuantity + $quantityToAdd;

        // 3. Validar Stock
        if ($game->stock < $totalRequested) {
            \Log::warning('Intento de añadir al carrito fallido por stock insuficiente', ['game_id' => $game->id, 'requested' => $totalRequested, 'stock' => $game->stock]);
            return back()->with('error', "No hay suficiente stock disponible para {$game->title}. Máximo disponible: {$game->stock}");
        }

        // 4. Guardar/Actualizar Item
        if ($cartItem) {
            $cartItem->update(['quantity' => $totalRequested]);
        } else {
            $cart->items()->create([
                'game_id' => $game->id,
                'quantity' => $quantityToAdd
            ]);
        }

        return redirect()->route('carrito.index')->with('success', "{$game->title} añadido al carrito.");
    }

    /**
     * Actualiza la cantidad de un item en el carrito.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = CartItem::findOrFail($id);
        $game = $cartItem->game;

        // Validar Stock
        if ($game->stock < $request->quantity) {
            return back()->with('error', "No hay suficiente stock para {$game->title}. Disponible: {$game->stock}");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', "Cantidad actualizada correctamente.");
    }

    /**
     * Elimina un item del carrito.
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', "Producto eliminado del carrito.");
    }

    /**
     * Vacía todo el carrito.
     */
    public function clear()
    {
        $cart = $this->getCart();
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', "El carrito se ha vaciado.");
    }

    /**
     * Lógica privada para obtener el carrito actual (solo usuarios logueados).
     */
    private function getCart()
    {
        return Cart::where('user_id', auth()->id())->first();
    }

    /**
     * Lógica privada para obtener o crear el carrito (solo usuarios logueados).
     */
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }
}
