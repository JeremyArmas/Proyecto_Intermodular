@extends('layouts.app')

@section('title', 'Pedidos • Administración')

@section('content')
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
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-3" style="background-color: var(--jg-mint-fade); border-color: var(--jg-mint); color: #fff;">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger mt-3" style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
        {{ session('error') }}
      </div>
    @endif

    <div class="jg-card p-3 mb-3">
      <div class="jg-table-wrap">
        <div class="table-responsive">
          <table class="table jg-table align-middle">
            <thead>
              <tr>
                <th>Pedido #</th>
                <th>Usuario</th>
                <th>Tipo</th>
                <th class="text-end">Total</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $o)
                @php
                  // 'pending', 'paid', 'shipped', 'cancelled'
                  $b = $o->status === 'paid' ? 'badge-primary' : ($o->status === 'shipped' ? 'badge-mint' : ($o->status === 'cancelled' ? 'badge-sun' : 'badge-soft'));
                  $statusLabels = [
                      'pending' => 'Pendiente',
                      'paid' => 'Pagado',
                      'shipped' => 'Enviado',
                      'cancelled' => 'Cancelado'
                  ];
                @endphp
                <tr>
                  <td>#{{ str_pad($o->id, 5, '0', STR_PAD_LEFT) }}</td>
                  <td>{{ $o->user->name ?? 'Usuario Eliminado' }} <br><small class="jg-muted">{{ $o->user->email ?? '' }}</small></td>
                  <td><span class="badge badge-soft">{{ strtoupper($o->order_type) }}</span></td>
                  <td class="text-end">{{ number_format($o->total_amount, 2) }} €</td>
                  <td><span class="badge {{ $b }}">{{ $statusLabels[$o->status] ?? $o->status }}</span></td>
                  <td class="text-nowrap">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.orders.show', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('admin.orders.edit', $o) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.orders.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('ATENCIÓN: ¿Seguro que deseas eliminar este pedido permanentemente?');">
                      @csrf
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
