@extends('layouts.app')

@section('title', 'Pedidos • Administración')

@section('content')

<!-- Vista para listar los pedidos en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Pedidos</h1>
          <div class="jg-muted">Historial de compras y gestión logística.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <!-- Muestra mensaje de éxito después de una acción -->
    @if(session('success'))
      <div class="alert alert-success mt-3" style="background-color: var(--jg-mint-fade); border-color: var(--jg-mint); color: #fff;">
        {{ session('success') }}
      </div>
    @endif
    
    <!-- Muestra mensaje de error después de una acción -->
    @if(session('error'))
      <div class="alert alert-danger mt-3" style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
        {{ session('error') }}
      </div>
    @endif

    <!-- Tabla para listar los pedidos -->
    <div class="jg-card p-3 mb-3">
      <div class="jg-table-wrap">
        <div class="table-responsive">
          <table class="table jg-table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th class="text-end">Total</th>
                <th>Estado</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>

              <!-- Recorremos los pedidos y los mostramos en la tabla -->
              @forelse($orders as $o)
                
                <!-- Para cada pedido mostramos su información en una fila -->
                @php
                  $b = $o->status === 'paid' ? 'badge-primary' : ($o->status === 'shipped' ? 'badge-mint' : ($o->status === 'cancelled' ? 'badge-sun' : 'badge-soft'));
                  $statusLabels = [
                      'pending' => 'Pendiente',
                      'paid' => 'Pagado',
                      'shipped' => 'Enviado',
                      'cancelled' => 'Cancelado'
                  ];
                @endphp
                
                <!-- Fila para cada pedido -->
                <tr>
                  
                  <!-- Mostramos el ID del pedido con formato -->
                  <td>#{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</td>
                  
                  <!-- Mostramos el nombre y email del usuario que hizo el pedido, o 'Usuario Eliminado' si el usuario ya no existe -->
                  <td>{{ $o->user->name ?? 'Usuario Eliminado' }} <br><small class="jg-muted">{{ $o->user->email ?? '' }}</small></td>
                  
                  <!-- Mostramos el tipo de pedido (digital o físico) con un badge -->
                  <td><span class="badge badge-soft">{{ strtoupper($o->order_type) }}</span></td>
                  
                  <!-- Mostramos el total del pedido formateado a 2 decimales y alineado a la derecha -->
                  <td class="text-end">{{ number_format($o->total_amount, 2) }} €</td>
                  
                  <!-- Mostramos el estado del pedido con un badge de color según el estado -->
                  <td><span class="badge {{ $b }}">{{ $statusLabels[$o->status] ?? $o->status }}</span></td>
                  
                  <!-- Mostramos la cantidad total de items en el pedido -->
                  <td class="text-nowrap">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                  
                  <!-- Mostramos la fecha de creación del pedido formateada -->
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('admin.orders.edit', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.orders.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENCIÓN: ¿Seguro que deseas eliminar este pedido permanentemente?');">
                      @csrf
                      
                      <!-- Usamos el método DELETE para eliminar el pedido -->
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 jg-muted">No hay pedidos registrados en el sistema.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
