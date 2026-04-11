@extends('layouts.app')

@section('title', 'Añadir Juego • Administración')

@section('content')

<!-- Vista para crear una nueva categoría de juegos en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container" style="max-width: 900px;">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Añadir Juego</h1>
        </div>
        <div>
          <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <!-- Muestra errores de validación si los hay -->
    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario para crear un nuevo juego -->
    <div class="jg-card p-4">
      <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Campos del formulario para el nuevo juego -->
        <div class="row g-3 mb-3">
          
          <!-- Campo de título -->
          <div class="col-md-8">
            <label for="title" class="form-label text-white">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
          <!-- Campo de stock -->
           <div class="col-md-4">
            <label for="stock" class="form-label text-white">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <!-- Campo de slug (se autoregenera) -->
        <div class="mb-3">
          <label for="slug" class="form-label text-white">Slug (URL)</label>
          <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <!-- Campo de descripción -->
        <div class="mb-3">
          <label for="description" class="form-label text-white">Descripción</label>
          <textarea class="form-control" id="description" name="description" rows="4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">{{ old('description') }}</textarea>
        </div>

        <!-- Campo de la URL del Trailer -->
        <div class="mb-3">
          <label for="trailer_url" class="form-label text-white">URL del Trailer de YouTube <small class="jg-muted">(Ej: https://www.youtube.com/watch?v=...)</small></label>
          <input type="url" class="form-control" id="trailer_url" name="trailer_url" value="{{ old('trailer_url') }}" placeholder="Enlace de YouTube" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <!-- Campo de precio -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="price" class="form-label text-white">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', '0.00') }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
        <!-- Campo de precio B2B opcional -->
          <div class="col-md-6">
          <label for="b2b_price" class="form-label text-white">Precio B2B opcional (€)</label>
          <input type="number" step="0.01" class="form-control" id="b2b_price" name="b2b_price" value="{{ old('b2b_price') }}" min="0" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>
        </div>

        <!-- Campo de Developer y Release Date -->
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="developer" class="form-label text-white">Desarrollador</label>
            <input type="text" class="form-control" id="developer" name="developer" value="{{ old('developer') }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          
          <div class="col-md-6">
            <label for="release_date" class="form-label text-white d-flex justify-content-between">Fecha de Lanzamiento (Próximamente) <small class="jg-muted">Opcional</small></label>
            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ old('release_date') }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff; color-scheme: dark;">
          </div>
        </div>

        <!-- Campo de Plataforma -->
        <div class="mb-3">
            <label for="platform_id" class="form-label text-white">Plataforma</label>
            <select class="form-select" id="platform_id" name="platform_id" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
              <option value="">Selecciona...</option>
              @foreach($platforms as $p)
                <option value="{{ $p->id }}" {{ old('platform_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
              @endforeach
            </select>
        </div>

        <!-- Campo de categorías -->
        <div class="mb-3">
          <label class="form-label text-white d-block">Categorías</label>
          <div class="row">
            @foreach($categories as $c)
              <div class="col-md-4 col-sm-6 mb-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $c->id }}" id="cat_{{ $c->id }}" {{ (is_array(old('categories')) && in_array($c->id, old('categories'))) ? 'checked' : '' }}>
                  <label class="form-check-label text-white" for="cat_{{ $c->id }}">
                    {{ $c->name }}
                  </label>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Campo de imagen de portada -->
        <div class="mb-4">
          <label for="cover_image" class="form-label text-white">Imagen de Portada</label>
          <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <!-- Campo de estado (publicado o no) -->
        <div class="mb-4 form-check form-switch p-0 d-flex align-items-center gap-2">
            <div class="form-check ms-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="is_active">Publicado (Visible en la tienda)</label>
            </div>
        </div>

        <!-- Botón para enviar el formulario -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Guardar juego
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script para generar el slug automáticamente a partir del título -->
<script>
  
  // Escucha el evento de input en el campo de título
  document.getElementById('title').addEventListener('input', function(e) {
    
    // Obtiene el valor del título
    let title = e.target.value;
    
    // Convierte el título a un slug amigable para URLs
    let slug = title.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    
    // Asigna el slug generado al campo de slug
    document.getElementById('slug').value = slug;
  });
  
</script>
@endsection
