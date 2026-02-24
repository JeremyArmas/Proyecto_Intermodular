@extends('layouts.app')

@section('title', 'Nueva Categoría • Administración')

@section('content')
<div class="jg-admin jg-admin-wrap">
  <div class="container" style="max-width: 800px;">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Añadir Categoría</h1>
        </div>
        <div>
          <a href="{{ route('admin.categories.index') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <div class="jg-card p-4">
      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label text-white">Nombre</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Acción, Aventura..." required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('name')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label for="slug" class="form-label text-white">Slug (URL amigable)</label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Ej: accion, aventura..." required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          <div class="form-text jg-muted mt-1">Este será el identificador en la URL (letras minúsculas y guiones).</div>
          @error('slug')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Guardar categoría
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Simple slug autosuggestion
  document.getElementById('name').addEventListener('input', function(e) {
    let title = e.target.value;
    // Replace non-alphanumeric characters with hyphens
    let slug = title.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    document.getElementById('slug').value = slug;
  });
</script>
@endsection
