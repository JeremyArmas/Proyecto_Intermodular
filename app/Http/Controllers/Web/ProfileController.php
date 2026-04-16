<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // Descarga la factura en pdf
    public function downloadOrderPdf($id){
        $order = auth()->user()->orders()->with('items.game')->findOrFail($id); // Buscamos el pedido por id

        $pdf = Pdf::loadView('profile.order-pdf', compact('order')); // Cargamos la vista de la factura

        return $pdf->download('Factura_Jediga_' . 'order-' . $order->id . '.pdf'); // Descargamos la factura
    }

    // Muestra el perfil del usuario
    public function show()
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

    // Mostrar la biblioteca del usuario. SOLO puede acceder a ellas los usuarios normales (Y los admin pero eso es cosa aparte).
    public function biblioteca() 
    {
        $user = auth()->user();

        // Si el usuario es una empresa, no puede acceder a la biblioteca. Lo ponemos como metodo de seguridad ya que si una empresa
        // intenta escribir por ejemplo /biblioteca en la url, no podra acceder.

        if ($user->isCompany()) { 
            return redirect()->route('profile.show')->with('error', 'No tienes permiso para acceder a la biblioteca. Esta es solo para usuarios normales.');
        }

        $items = \App\Models\OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)->
            where('status', 'paid')->
            where('order_type', 'b2c');
        })->with('game') //Carga todos los juegos que ha comprado el usuario
        ->get(); 

        return view('profile.biblioteca', compact('items'));
    }

    public function confirmDelivery($id) // Confirmar la entrega del pedido
    {
        $user = auth()->user(); // Obtenemos el usuario logueado
        $order = \App\Models\Order::where('user_id', $user->id)->findOrFail($id); // Buscamos el pedido por id

        if ($order->order_type !== 'b2b' || $order->tracking_status !== 'delivered') { // Si el pedido no es de tipo b2b o si no esta entregado, no se puede confirmar la entrega
            return redirect()->route('profile.orders')->with('error', 'No puedes confirmar la entrega de este pedido.');
        }

        $order->delivered_confirmed_at = now(); // Actualizamos la fecha de confirmación de entrega
        $order->save(); // Guardamos el pedido
        return redirect()->route('profile.orders')->with('success', 'Entrega confirmada correctamente'); // Redirigimos al perfil con un mensaje de éxito
    }

}
