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
            <thead style="border-bottom: 2px solid rgba(255,255,255,0.1);">
              <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th class="text-end">Total</th>
                <th class="text-center">Estado</th>
                <th>Fecha</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>

              <!-- Recorremos los pedidos y los mostramos en la tabla -->
              @forelse($orders as $o)
                
                <!-- Definición de estados y estilos visuales -->
                @php
                  $statusLabels = [
                      'pending' => 'Pendiente',
                      'paid' => 'Pagado',
                      'shipped' => 'Enviado',
                      'cancelled' => 'Cancelado'
                  ];
                  
                  // Estilos brillantes y claros (mismo estilo que frontend)
                  $badgeStyle = '';
                  if ($o->status === 'paid') {
                      $badgeStyle = 'background: rgba(0, 255, 157, 0.15); color: #00ff9d; border: 1px solid rgba(0, 255, 157, 0.3);';
                  } elseif ($o->status === 'pending') {
                      $badgeStyle = 'background: rgba(255, 204, 0, 0.15); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.3);';
                  } elseif ($o->status === 'shipped') {
                      $badgeStyle = 'background: rgba(0, 195, 255, 0.15); color: #00c3ff; border: 1px solid rgba(0, 195, 255, 0.3);';
                  } elseif ($o->status === 'cancelled') {
                      $badgeStyle = 'background: rgba(255, 71, 87, 0.15); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.3);';
                  } else {
                      $badgeStyle = 'background: rgba(255, 255, 255, 0.1); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2);';
                  }
                @endphp
                
                <!-- Fila para cada pedido -->
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.02);">
                  
                  <!-- Mostramos el ID del pedido con formato -->
                  <td class="fw-bold opacity-75">#{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</td>
                  
                  <!-- Mostramos el nombre y email del usuario que hizo el pedido, o 'Usuario Eliminado' si el usuario ya no existe -->
                  <td>
                    <span class="d-block">{{ $o->user->name ?? 'Usuario Eliminado' }}</span>
                    <small class="text-white opacity-50">{{ $o->user->email ?? '' }}</small>
                  </td>
                  
                  <!-- Mostramos el tipo de pedido (digital o físico) con un badge -->
                  <td><span class="badge" style="background: rgba(255,255,255,0.05); color: #aaa; border: 1px solid rgba(255,255,255,0.1);">{{ strtoupper($o->order_type) }}</span></td>
                  
                  <!-- Mostramos el total del pedido formateado a 2 decimales y alineado a la derecha -->
                  <td class="text-end fw-bold" style="color: #00ff9d;">{{ \App\Services\CurrencyService::format($o->total_amount) }}</td>
                  
                  <!-- Mostramos el estado del pedido con un badge de color según el estado -->
                  <td class="text-center">
                    <span class="badge px-3 py-2 rounded-pill" style="{{ $badgeStyle }} font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">
                      {{ $statusLabels[$o->status] ?? ucfirst($o->status) }}
                    </span>
                  </td>
                  
                  <!-- Mostramos la fecha de creación del pedido formateada -->
                  <td class="text-nowrap text-white opacity-75">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                  
                  <!-- Acciones -->
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('admin.orders.edit', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.orders.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENCIÓN: ¿Seguro que deseas eliminar este pedido permanentemente?');">
                      @csrf
                      
                      <!-- Usamos el método DELETE para eliminar el pedido -->
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>

                    <!-- Descargar PDF -->
                    <a href="{{ route('admin.orders.download', $o->id) }}" class="btn btn-sm jg-btn jg-btn-outline" target="_blank" title="Descargar factura en PDF"><i class="bi bi-download"></i></a>
                    <!--Creamos un enlace para que usa el ID del pedido para descargar la factura-->
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
