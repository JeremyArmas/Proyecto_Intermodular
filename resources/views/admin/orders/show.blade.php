@extends('layouts.app')

@section('title', 'Detalles del Pedido • Administración')

@section('content')

<!-- Vista para mostrar los detalles de un pedido en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container" style="max-width: 900px;">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Pedido #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        </div>
        <div>
          <a href="{{ route('admin.orders.index') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver a pedidos
          </a>
        </div>
      </div>
    </div>

    <!-- Muestra mensaje de éxito después de una acción -->
    <div class="row g-4">
      <div class="col-md-8">
        <div class="jg-card p-4 h-100">
          <h5 class="text-white mb-3">Información del Pedido</h5>
          <table class="table jg-table">
            <tbody>
              <tr>
                <th style="width: 30%">Estado:</th>
                <td>
                  
                  <!-- Mostramos el estado del pedido con una etiqueta de color -->
                  @php
                    $statusLabels = ['pending' => 'Pendiente', 'paid' => 'Pagado', 'shipped' => 'Enviado', 'cancelled' => 'Cancelado'];
                    $badgeStyle = '';
                    if ($order->status === 'paid') {
                        $badgeStyle = 'background: rgba(0, 255, 157, 0.15); color: #00ff9d; border: 1px solid rgba(0, 255, 157, 0.3);';
                    } elseif ($order->status === 'pending') {
                        $badgeStyle = 'background: rgba(255, 204, 0, 0.15); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.3);';
                    } elseif ($order->status === 'shipped') {
                        $badgeStyle = 'background: rgba(0, 195, 255, 0.15); color: #00c3ff; border: 1px solid rgba(0, 195, 255, 0.3);';
                    } elseif ($order->status === 'cancelled') {
                        $badgeStyle = 'background: rgba(255, 71, 87, 0.15); color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.3);';
                    } else {
                        $badgeStyle = 'background: rgba(255, 255, 255, 0.1); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2);';
                    }
                  @endphp
                  <span class="badge px-3 py-2 rounded-pill" style="{{ $badgeStyle }} font-size: 0.85rem; letter-spacing: 0.5px; font-weight: 600;">
                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                  </span>
                </td>
              </tr>
              
              <!-- Mostramos el resto de la información del pedido en una tabla -->
              
              <!-- Formateamos la fecha de compra y el coste total para una mejor presentación -->
              <tr>
                <th>Fecha de compra:</th>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
              </tr>
              
              <!-- Mostramos el tipo de pedido en mayúsculas para resaltar esta información -->
              <tr>
                <th>Tipo de pedido:</th>
                <td>{{ strtoupper($order->order_type) }}</td>
              </tr>
              
              <!-- Formateamos el coste total con dos decimales y el símbolo de euro para una mejor presentación -->
              <tr>
                <th>Coste Total:</th>
                <td class="fw-bold" style="color: var(--jg-mint);">{{ number_format($order->total_amount, 2) }} €</td>
              </tr>
              
              <!-- Mostramos la dirección de envío o un mensaje si no está especificada -->
              <tr>
                <th>Dirección de envío:</th>
                <td>{{ $order->shipping_address ?? 'No especificada' }}</td>
              </tr>
            </tbody>
          </table>
          
          <!-- Botón para actualizar el estado del pedido, que redirige a la página de edición del pedido -->
          <div class="mt-4">
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn jg-btn jg-btn-outline w-100">Actualizar Estado</a>
          </div>
        </div>
      </div>

      <!-- Muestra los datos del cliente que realizó el pedido, o un mensaje si el usuario ha sido eliminado -->
      <div class="col-md-4">
        <div class="jg-card p-4 h-100">
          <h5 class="text-white mb-3">Datos del Cliente</h5>
          
          <!-- Verificamos si el usuario asociado al pedido existe antes de mostrar su información -->
          @if($order->user)
            <div class="mb-2"><strong>ID:</strong> #{{ $order->user->id }}</div>
            <div class="mb-2"><strong>Nombre:</strong> {{ $order->user->name }}</div>
            <div class="mb-2"><strong>Email:</strong> {{ $order->user->email }}</div>
            <div class="mb-2"><strong>Rol:</strong> <span class="badge badge-soft">{{ ucfirst($order->user->role) }}</span></div>
            <div class="mt-4">
               <a href="{{ route('admin.users.edit', $order->user) }}" class="btn jg-btn jg-btn-outline w-100">Ver Perfil</a>
            </div>
          @else
            <div class="jg-muted">El usuario que realizó este pedido ha sido eliminado de la base de datos.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
