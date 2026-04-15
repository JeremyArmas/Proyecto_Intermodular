@extends('layouts.app')

@section('title', 'Actualizar Pedido • Administración')

@section('content')

  <!-- Vista para editar el estado de un pedido en el panel de administración -->
  <div class="jg-admin jg-admin-wrap">
    <div class="container" style="max-width: 600px;">
      <div class="jg-admin-header p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div>
            <div class="jg-pill mb-2">
              <span class="jg-dot"></span>
              <span>Panel de administración</span>
            </div>
            <h1 class="jg-section-title h3 mb-0" style="display:block;">Actualizar Pedido
              #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
          </div>
          <div>
            <a href="{{ route('admin.orders.index') }}" class="btn jg-btn jg-btn-outline">
              <i class="bi bi-arrow-left me-1"></i> Volver a pedidos
            </a>
          </div>
        </div>
      </div>

      <!-- Mostrar errores de validación si los hay -->
      @if ($errors->any())
        <div class="alert alert-danger"
          style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Tarjeta con el formulario para actualizar el estado del pedido -->
      <div class="jg-card p-4">
        <div class="mb-4">
          <p class="mb-1"><strong>Cliente:</strong> {{ $order->user->name ?? 'Usuario Eliminado' }}</p>
          <p class="mb-1"><strong>Total:</strong> {{ number_format($order->total_amount, 2) }} €</p>
          <p class="mb-0"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        </div>

        <!-- Formulario para actualizar el estado del pedido -->
        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
          @csrf

          <!-- Usamos el método PUT para actualizar el pedido -->
          @method('PUT')

          <!-- Campo para seleccionar el nuevo estado del pedido -->
          <div class="mb-4">
            <label for="status" class="form-label text-white">Estado del pedido</label>
            <select class="form-select" id="status" name="status" required
              style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
              <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
              <option value="paid" {{ old('status', $order->status) == 'paid' ? 'selected' : '' }}>Pagado</option>
              <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Enviado</option>
              <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelado
              </option>
            </select>
            <div class="form-text jg-muted mt-2">
              El cambio de estado se reflejará instantáneamente en el panel del administrador y del cliente.
            </div>
            <!--Campo para el rastreo (solo para las empresas)-->
            @if($order->order_type === 'b2b')
              <div class="mb-4">
                <label for="tracking_status" class="form-label text-white">Estado del seguimiento</label>
                <select class="form-select" id="tracking_status" name="tracking_status" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;" {{ $order->delivered_confirmed_at ? 'disabled' : '' }}> <!-- Desplegable para el estado del seguimiento-->
                  <option value="in_warehouse" {{ old('tracking_status', $order->tracking_status) == 'in_warehouse' ? 'selected' : '' }}>En el almacén</option> <!-- El pedido está en el almacén-->
                  <option value="shipped_out" {{ old('tracking_status', $order->tracking_status) == 'shipped_out' ? 'selected' : '' }}>Salió del almacén</option> <!-- El pedido salió del almacén-->
                  <option value="with_courier" {{ old('tracking_status', $order->tracking_status) == 'with_courier' ? 'selected' : '' }}>En la empresa de reparto</option> <!-- El pedido está en la empresa de reparto-->
                  <option value="on_the_way" {{ old('tracking_status', $order->tracking_status) == 'on_the_way' ? 'selected' : '' }}>En camino al cliente</option> <!-- El pedido está en camino al cliente-->
                  <option value="delivered" {{ old('tracking_status', $order->tracking_status) == 'delivered' ? 'selected' : '' }}>Entregado</option> <!-- El pedido fue entregado-->
                </select>
                <div class="form-text jg-muted mt-2">
                  @if($order->delivered_confirmed_at)
                    El pedido ha sido entregado y confirmado por el cliente. No se puede cambiar el estado del seguimiento.
                  @else
                    El cambio de estado se reflejará instantáneamente en el panel del administrador y del cliente.
                  @endif
                </div> <!--Aviso para las empresas-->
              </div>
              @if($order->delivered_confirmed_at) <!--Si el pedido ha sido entregado y confirmado-->
                <div class="alert alert-success d-flex align-items-center mt-3" style="background: rgba(0, 255, 157, 0.1); border-color: var(--jg-mint); color: #fff;">
                  <i class="bi bi-check-circle me-2"></i>
                  El pedido <strong>ha sido entregado </strong> y confirmado por el cliente. <br> La empresa confirmó la recepción el {{ $order->delivered_confirmed_at->format('d/m/Y \a \l\a\s H:i') }}.
                </div> 
              @endif
            @endif

          </div>

          <!-- Botón para guardar los cambios -->
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn jg-btn jg-btn-primary w-100">
              Guardar cambios
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection