<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Muestra el historial de pedidos del usuario.
     */
    public function orders()
    {
        // Obtenemos los pedidos del usuario logueado, ordenados por fecha descendente
        // y cargamos las relaciones necesarias (items y juegos)
        $orders = auth()->user()->orders()->with('items.game')->latest()->get();

        return view('profile.orders', compact('orders'));
    }
}
