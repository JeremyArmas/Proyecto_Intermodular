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

    public function downloadOrderPdf($id){ // Descargar la factura en pdf
        $order = auth()->user()->orders()->with('items.game')->findOrFail($id); // Buscamos el pedido por id

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('profile.order-pdf', compact('order')); // Cargamos la vista de la factura

        return $pdf->download('Factura_Jediga_' . 'order-' . $order->id . '.pdf'); // Descargamos la factura
    }

    public function show() // Mostrar el perfil del usuario
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:30',
            'country' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        //Actualizamos los datos del usuario
        auth()->user()->update([
            'name' => $request->name,
            'country' => $request->country,
            'birth_date' => $request->birth_date,
        ]);

        // Para subir la imagen para personalizar tu perfil . El logo de tu usuario o empresa o como quieras.

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Guardamos la nueva foto primero
            if ($file->move(public_path('avatars'), $filename)) {
                // Solo si se guardó bien, borramos la anterior
                if ($user->avatar) @unlink(public_path('avatars/' . $user->avatar));
                $user->avatar = $filename;
                $user->save();
            }
        }

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente');
    }

}
