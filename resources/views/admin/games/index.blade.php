@extends('layouts.app')

@section('title', 'Juegos • Administración')

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
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Juegos</h1>
          <div class="jg-muted">Catálogo principal de la tienda.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.games.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Añadir juego
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
                <th style="width: 60px">Foto</th>
                <th>Nombre</th>
                <th>Plataforma</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Stock</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($games as $g)
                <tr>
                  <td>
                    @if($g->cover_image)
                      <img src="{{ asset('storage/' . $g->cover_image) }}" alt="Cover" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    @else
                      <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-image" style="color: rgba(255,255,255,0.3)"></i>
                      </div>
                    @endif
                  </td>
                  <td class="fw-bold">
                    {{ $g->title }}
                    <div class="small jg-muted">
                        @foreach($g->categories as $c)
                            {{ $c->name }}@if(!$loop->last), @endif
                        @endforeach
                    </div>
                  </td>
                  <td><span class="badge badge-soft">{{ $g->platform->name ?? 'N/A' }}</span></td>
                  <td class="text-end">{{ number_format($g->price, 2) }} €</td>
                  <td class="text-end">
                    <span class="badge {{ $g->stock <= 0 ? 'badge-sun' : ($g->stock <= 10 ? 'badge-sun' : 'badge-soft') }}">
                      {{ $g->stock }}
                    </span>
                  </td>
                  <td>
                    <span class="badge {{ $g->is_active ? 'badge-mint' : 'badge-soft' }}">
                      {{ $g->is_active ? 'Publicado' : 'Borrador' }}
                    </span>
                  </td>
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.games.edit', $g) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.games.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este juego?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 jg-muted">No hay juegos registrados en el sistema.</td>
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
