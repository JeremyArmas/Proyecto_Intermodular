@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administrar Pedidos</h1>
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary mb-3">Añadir Pedido</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user_id }}</td>
                <td>${{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar pedido?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
