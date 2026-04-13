@extends('layouts.app')

@section('title', 'Tickets • Administración')

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
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Tickets de Soporte</h1>
          <div class="jg-muted">Bandeja de entrada de los mensajes de los clientes.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <!-- Botón para volver al Panel -->
          <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver al panel
          </a>
        </div>
      </div>
    </div>

    <!-- Si hay algún error (Ej: el ticket está ocupado por otro admin) -->
    @if(session('error'))
      <div class="alert alert-danger mt-3" style="background-color: rgba(220,53,69,0.1); border-color: #dc3545; color: #ff6b6b;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
      </div>
    @endif

    <div class="jg-card p-3 mb-3">
      <div class="jg-table-wrap">
        <div class="table-responsive">
          <table class="table jg-table align-middle">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Recibido</th>
                <th class="text-end">Acción</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tickets as $t)
                <tr>
                  <!-- Nombre y correo -->
                  <td>
                    <span class="fw-bold">{{ $t->name }}</span><br>
                    <small class="jg-muted">{{ $t->email }}</small>
                  </td>

                  <!-- Asunto -->
                  <td class="fw-bold">{{ $t->subject }}</td>

                  <!-- Estado -->
                  <td>
                    @if($t->status === 'pendiente')
                      <span class="badge badge-sun">Pendiente</span>
                    @else
                      <span class="badge badge-mint">Respondido</span>
                    @endif
                  </td>

                  <!-- Fecha -->
                  <td class="text-nowrap">{{ $t->created_at->format('Y-m-d H:i') }}</td>

                  <!-- Botón para leer -->
                  <td class="text-end">
                    <a href="{{ route('admin.tickets.show', $t->id) }}" class="btn btn-sm jg-btn jg-btn-outline">
                      <i class="bi bi-envelope-open"></i> Leer
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 jg-muted">No hay tickets de soporte.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Paginación -->
      <div class="mt-3 d-flex justify-content-end">
        {{ $tickets->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
