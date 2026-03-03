@extends('layouts.app')

@section('title', 'Editar Categoría • Administración')

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
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Editar Categoría</h1>
        </div>
        <div>
          <a href="{{ route('admin.categories.index') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <div class="jg-card p-4">
      <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label text-white">Nombre</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('name')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label for="slug" class="form-label text-white">Slug (URL amigable)</label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('slug')
            <div class="invalid-feedback" style="color: var(--jg-sun);">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Actualizar categoría
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
