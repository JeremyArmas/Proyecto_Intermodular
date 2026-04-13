@extends('layouts.app')

@section('title', 'Plataformas • Administración')

@section('content')

<!-- Vista para listar y gestionar las plataformas en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Plataformas</h1>
          <div class="jg-muted">Gestión de plataformas soportadas en la tienda.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.platforms.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva plataforma
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

    <!-- Tabla para listar las plataformas con opciones de edición y eliminación -->
    <div class="jg-card p-3 mb-3">
      <div class="jg-table-wrap">
        <div class="table-responsive">
          <table class="table jg-table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Actualizado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              
              <!-- Recorremos las plataformas y mostramos cada una en una fila de la tabla, con opciones para editar o eliminar -->
              @forelse($platforms as $p)
                <tr>
                  <td>#{{ $p->id }}</td>
                  <td class="fw-bold">{{ $p->name }}</td>
                  <td><span class="badge badge-soft">{{ $p->slug }}</span></td>
                  <td class="text-nowrap">{{ $p->updated_at->format('Y-m-d') }}</td>
                  <td class="text-end">
                    <a href="{{ route('admin.platforms.edit', $p) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.platforms.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta plataforma?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 jg-muted">No hay plataformas creadas aún.</td>
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
