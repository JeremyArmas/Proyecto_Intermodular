@extends('layouts.app')

@section('title', 'Editar Juego • Administración')

@section('content')

<!-- Vista para editar un juego en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container" style="max-width: 900px;">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Editar Juego: {{ $game->title }}</h1>
        </div>
        <div>
          <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para editar el juego -->
    <div class="jg-card p-4">
      <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Laravel no soporta PUT/PATCH en formularios, así que usamos POST y luego indicamos el método real -->
        @method('PUT')

        <!-- Campos del formulario -->
        <div class="row g-3 mb-3">
          <div class="col-md-8">
            <label for="title" class="form-label text-white">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $game->title) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
          <!-- Campo de stock (opcional) -->
          <div class="col-md-4">
            <label for="stock" class="form-label text-white">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $game->stock) }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <!-- Campo del slug (opcional) -->
        <div class="mb-3">
          <label for="slug" class="form-label text-white">Slug (URL)</label>
          <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $game->slug) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <!-- Campo de la descripción -->
        <div class="mb-3">
          <label for="description" class="form-label text-white">Descripción</label>
          <textarea class="form-control" id="description" name="description" rows="4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">{{ old('description', $game->description) }}</textarea>
        </div>

        <!-- Campo del precio -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="price" class="form-label text-white">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $game->price) }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
          <!-- Campo del precio B2B (opcional) -->
          <div class="col-md-6">
            <label for="b2b_price" class="form-label text-white">Precio B2B opcional (€)</label>
            <input type="number" step="0.01" class="form-control" id="b2b_price" name="b2b_price" value="{{ old('b2b_price', $game->b2b_price) }}" min="0" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <!-- Campo del desarrollador -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="developer" class="form-label text-white">Desarrollador</label>
            <input type="text" class="form-control" id="developer" name="developer" value="{{ old('developer', $game->developer) }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
          <!-- Campo de la plataforma -->
          <div class="col-md-6">
            <label for="platform_id" class="form-label text-white">Plataforma</label>
            <select class="form-select" id="platform_id" name="platform_id" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
              <option value="">Selecciona...</option>
              @foreach($platforms as $p)
                <option value="{{ $p->id }}" {{ old('platform_id', $game->platform_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Campo de las categorías -->
        <div class="mb-3">
          <label class="form-label text-white d-block">Categorías</label>
          <div class="row">
            
            <!-- Para mantener las categorías seleccionadas después de una validación fallida, obtenemos los IDs de las categorías actuales del juego o de la entrada anterior -->
            @php
              $selectedCats = old('categories', $game->categories->pluck('id')->toArray());
            @endphp
            
            <!-- Listamos todas las categorías disponibles con checkboxes -->
            @foreach($categories as $c)
              <div class="col-md-4 col-sm-6 mb-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $c->id }}" id="cat_{{ $c->id }}" {{ in_array($c->id, $selectedCats) ? 'checked' : '' }}>
                  <label class="form-check-label text-white" for="cat_{{ $c->id }}">
                    {{ $c->name }}
                  </label>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Campo de la imagen de portada -->
        <div class="mb-4">
          <label for="cover_image" class="form-label text-white d-block">Imagen de Portada Actual</label>
          @if($game->cover_image)
            <img src="{{ asset('storage/' . $game->cover_image) }}" alt="Cover" style="height: 120px; border-radius: 8px; margin-bottom: 10px; object-fit: cover;">
          @endif
          <input type="file" class="form-control mt-2" id="cover_image" name="cover_image" accept="image/*" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          <small class="jg-muted">Si subes una nueva imagen, reemplazará a la actual.</small>
        </div>

        <!-- Campo para marcar el juego como activo o inactivo -->
        <div class="mb-4 d-flex align-items-center gap-2">
            <div class="form-check ms-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $game->is_active) ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="is_active">Publicado (Visible en la tienda)</label>
            </div>
        </div>

        <!-- Botón para enviar el formulario -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Actualizar juego
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
