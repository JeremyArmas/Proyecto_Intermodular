@extends('layouts.app')

@section('title', 'Categorías • Administración')

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
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Categorías</h1>
          <div class="jg-muted">Gestión de categorías/géneros publicados.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.categories.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva categoría
          </a>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-3" style="background-color: var(--jg-mint-fade); border-color: var(--jg-mint); color: #fff;">
        {{ session('success') }}
      </div>
    @endif

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
              @forelse($categories as $c)
                <tr>
                  <td>#{{ $c->id }}</td>
                  <td class="fw-bold">{{ $c->name }}</td>
                  <td><span class="badge badge-soft">{{ $c->slug }}</span></td>
                  <td class="text-nowrap">{{ $c->updated_at->format('Y-m-d') }}</td>
                  <td class="text-end">
                    <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 jg-muted">No hay categorías creadas aún.</td>
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
