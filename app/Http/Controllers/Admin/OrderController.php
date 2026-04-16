<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // Generalmente no se crean pedidos manualmente desde aquí
        return redirect()->route('admin.orders.index')->with('error', 'La creación manual de pedidos no está habilitada.');
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
        $order->load(['user']); // Cargar también los items si existieran luego
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
            'status' => 'required|in:pending,paid,shipped,cancelled', // Estados del pedido
            'tracking_status' => 'nullable|in:in_warehouse,shipped_out,with_courier,on_the_way,delivered', // Estados de seguimiento del pedido
        ]);

        $order->status = $request->status; // Actualizamos el estado del pedido
        
        if ($request->has('tracking_status')) { // Si el pedido tiene un estado de seguimiento (Solo para empresas)
            $order->tracking_status = $request->tracking_status; // Actualizamos el estado de seguimiento del pedido
        }

        $order->save(); // Guardamos el pedido

        return redirect()->route('admin.orders.index')->with('success', 'Estado del pedido actualizado correctamente.'); // Redirigimos a la lista de pedidos con un mensaje de éxito
    }

    /**
     * Elimina el recurso especificado de la base de datos.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pedido eliminado de la base de datos.');
    }

    // Función que descarga la factura en pdf
    public function downloadOrderPdf($id){ 
        $order = Order::with('items.game')->findOrFail($id); // Busca un pedido por la id y carga los items y juegos

        $pdf = Pdf::loadView('profile.order-pdf', compact('order')); // Cargamos la vista de la factura

        return $pdf->download('Factura_Jediga_' . 'order-' . $order->id . '.pdf'); // Descargamos la factura
    }

    // Función que exporta todas las facturas en un zip
    public function exportAllPdfs(){ 
        $orders = Order::with('items.game')->get(); // Busca todos los pedidos

        // Si no hay pedidos, redirigimos atrás con un mensaje de aviso
        if ($orders->isEmpty()) {
            return back()->with('error', 'No hay pedidos registrados en el sistema para realizar la exportación.');
        }
        
        $zip = new \ZipArchive(); // Creamos un zip
        $zipFileName = 'Facturas_de_los_pedidos_jediga.zip'; // Nombre del zip
        $zipPath = storage_path('app/public/' . $zipFileName); // Ruta del zip

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) { // Si no se pudo crear el zip , devuelve un error
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        foreach ($orders as $order) { // Recorremos todos los pedidos
            $pdf = Pdf::loadView('profile.order-pdf', compact('order')); // Cargamos la vista de la factura
            $zip->addFromString('Factura_Jediga_order-' . $order->id . '.pdf', $pdf->output()); // Añadimos la factura al zip
        }

        $zip->close(); // Cerramos el zip

        // Verificamos si el archivo existe antes de intentar descargarlo
        if (!file_exists($zipPath)) {
            return back()->with('error', 'Hubo un error al generar el archivo de exportación.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true); // Descargamos el zip y lo eliminamos después de enviarlo.
    }
}
