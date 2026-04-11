@extends('layouts.app')

@section('title', 'Nueva Plataforma • Administración')

@section('content')

<!-- Vista para crear una nueva plataforma en el panel de administración -->
<div class="jg-admin jg-admin-wrap">
  <div class="container" style="max-width: 800px;">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Añadir Plataforma</h1>
        </div>
        <div>
          <a href="{{ route('admin.platforms.index') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <!-- Muestra mensaje de éxito después de guardar la plataforma -->
    <div class="jg-card p-4">
      <form action="{{ route('admin.platforms.store') }}" method="POST">
        @csrf

        <!-- Campo para el nombre de la plataforma, con validación y estilos personalizados -->
        <div class="mb-3">
          <label for="name" class="form-label text-white">Nombre</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: PlayStation 5, PC, Xbox Series..." required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('name')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <!-- Campo para el slug de la plataforma, con validación y estilos personalizados -->
        <div class="mb-4">
          <label for="slug" class="form-label text-white">Slug (URL amigable)</label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Ej: playstation-5, pc..." required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          <div class="form-text jg-muted mt-1">Este será el identificador en la URL.</div>
          @error('slug')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <!-- Botón para enviar el formulario y guardar la nueva plataforma -->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Guardar plataforma
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script para generar automáticamente el slug a partir del nombre de la plataforma, convirtiendo a minúsculas, eliminando acentos y caracteres especiales -->
<script>
  
  // Escuchamos el evento de entrada en el campo de nombre para actualizar el slug en tiempo real
  document.getElementById('name').addEventListener('input', function(e) {
    
    // Tomamos el valor del nombre, lo convertimos a minúsculas, eliminamos acentos y caracteres especiales, y lo formateamos como un slug
    let title = e.target.value;
    
    // Convertimos el título a un slug amigable para URLs
    let slug = title.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    
    // Actualizamos el valor del campo slug con el resultado formateado
    document.getElementById('slug').value = slug;
  });

</script>
@endsection
