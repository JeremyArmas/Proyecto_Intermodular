<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\OrderItem;

class ProfileController extends Controller
{
    /**
     * Devuelve el usuario autenticado independientemente del guard (web o admin).
     */
    private function activeUser()
    {
        return auth('web')->user() ?? auth('admin')->user();
    }

    private function isAdmin(): bool
    {
        return auth('admin')->check();
    }
    /**
     * Muestra el historial de pedidos del usuario.
     */
    public function orders()
    {
        // Obtenemos el usuario logueado (sea admin o usuario normal)
        $user = $this->activeUser();

        // Cargamos los pedidos del usuario con sus items y juegos
        $orders = $user->orders()->with('items.game')->latest()->get();

        return view('profile.orders', compact('orders'));
    }

    // Descarga la factura en pdf
    public function downloadOrderPdf($id){
        $user = $this->activeUser();

        $order = $user->orders()->with('items.game')->findOrFail($id); // Buscamos el pedido por id

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
        $user = $this->activeUser();

        $request->validate([
            'name' => 'required|string|max:30',
            'country' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        //Actualizamos los datos del usuario
        $user->update([
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
        // Los administradores pueden ver la biblioteca pero no tienen compras propias
        $user = $this->activeUser();

        // Si el usuario es una empresa, no puede acceder a la biblioteca.
        if ($user->isCompany()) { 
            return redirect()->route('profile.show')->with('error', 'No tienes permiso para acceder a la biblioteca. Esta es solo para usuarios normales.');
        }

        $items = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
            ->where('status', 'paid')
            ->where('order_type', 'b2c');
        })->with('game')
        ->get(); 

        return view('profile.biblioteca', compact('items'));
    }

    public function confirmDelivery($id) // Confirmar la entrega del pedido
    {
        $user = $this->activeUser(); // Obtenemos el usuario logueado
        $order = Order::where('user_id', $user->id)->findOrFail($id); // Buscamos el pedido por id

        if ($order->order_type !== 'b2b' || $order->tracking_status !== 'delivered') { // Si el pedido no es de tipo b2b o si no esta entregado, no se puede confirmar la entrega
            return redirect()->route('profile.orders')->with('error', 'No puedes confirmar la entrega de este pedido.');
        }

        $order->delivered_confirmed_at = now(); // Actualizamos la fecha de confirmación de entrega
        $order->save(); // Guardamos el pedido
        return redirect()->route('profile.orders')->with('success', 'Entrega confirmada correctamente'); // Redirigimos al perfil con un mensaje de éxito
    }

}
