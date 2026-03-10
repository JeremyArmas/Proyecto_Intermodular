<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index()
    {
        $orders = Order::all();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        // Generalmente no se crean pedidos manualmente desde aquÃ­
        return redirect()->route('admin.orders.index')->with('error', 'La creaciÃ³n manual de pedidos no estÃ¡ habilitada.');
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(Order $order)
    {
        $order->load(['user']); // Cargar tambiÃ©n los items si existieran luego
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Actualiza el recurso especificado en la base de datos.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.panel', ['#pedidos'])->with('success', 'Estado del pedido actualizado correctamente.');
    }

    /**
     * Elimina el recurso especificado de la base de datos.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.panel', ['#pedidos'])->with('success', 'Pedido eliminado de la base de datos.');
    }
}
