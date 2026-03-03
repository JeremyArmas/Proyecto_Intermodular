@extends('layouts.app')

@section('title', 'Detalles del Pedido • Administración')

@section('content')
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

    <div class="row g-4">
      <div class="col-md-8">
        <div class="jg-card p-4 h-100">
          <h5 class="text-white mb-3">Información del Pedido</h5>
          <table class="table jg-table">
            <tbody>
              <tr>
                <th style="width: 30%">Estado:</th>
                <td>
                  @php
                    $statusLabels = ['pending' => 'Pendiente', 'paid' => 'Pagado', 'shipped' => 'Enviado', 'cancelled' => 'Cancelado'];
                    $b = $order->status === 'paid' ? 'badge-primary' : ($order->status === 'shipped' ? 'badge-mint' : ($order->status === 'cancelled' ? 'badge-sun' : 'badge-soft'));
                  @endphp
                  <span class="badge {{ $b }}">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                </td>
              </tr>
              <tr>
                <th>Fecha de compra:</th>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
              </tr>
              <tr>
                <th>Tipo de pedido:</th>
                <td>{{ strtoupper($order->order_type) }}</td>
              </tr>
              <tr>
                <th>Coste Total:</th>
                <td class="fw-bold" style="color: var(--jg-mint);">{{ number_format($order->total_amount, 2) }} €</td>
              </tr>
              <tr>
                <th>Dirección de envío:</th>
                <td>{{ $order->shipping_address ?? 'No especificada' }}</td>
              </tr>
            </tbody>
          </table>
          <div class="mt-4">
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn jg-btn jg-btn-outline w-100">Actualizar Estado</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="jg-card p-4 h-100">
          <h5 class="text-white mb-3">Datos del Cliente</h5>
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
