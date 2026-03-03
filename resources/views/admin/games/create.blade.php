@extends('layouts.app')

@section('title', 'Añadir Juego • Administración')

@section('content')
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

    @if ($errors->any())
        <div class="alert alert-danger" style="background-color: var(--jg-sun-fade); border-color: var(--jg-sun); color: #fff;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="jg-card p-4">
      <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3 mb-3">
          <div class="col-md-8">
            <label for="title" class="form-label text-white">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          <div class="col-md-4">
            <label for="stock" class="form-label text-white">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <div class="mb-3">
          <label for="slug" class="form-label text-white">Slug (URL)</label>
          <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <div class="mb-3">
          <label for="description" class="form-label text-white">Descripción</label>
          <textarea class="form-control" id="description" name="description" rows="4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">{{ old('description') }}</textarea>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="price" class="form-label text-white">Precio (€)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', '0.00') }}" min="0" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          <div class="col-md-6">
            <label for="b2b_price" class="form-label text-white">Precio B2B opcional (€)</label>
            <input type="number" step="0.01" class="form-control" id="b2b_price" name="b2b_price" value="{{ old('b2b_price') }}" min="0" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label for="developer" class="form-label text-white">Desarrollador</label>
            <input type="text" class="form-control" id="developer" name="developer" value="{{ old('developer') }}" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          <div class="col-md-6">
            <label for="platform_id" class="form-label text-white">Plataforma</label>
            <select class="form-select" id="platform_id" name="platform_id" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
              <option value="">Selecciona...</option>
              @foreach($platforms as $p)
                <option value="{{ $p->id }}" {{ old('platform_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

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

        <div class="mb-4">
          <label for="cover_image" class="form-label text-white">Imagen de Portada</label>
          <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <div class="mb-4 form-check form-switch p-0 d-flex align-items-center gap-2">
            <div class="form-check ms-3">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label text-white" for="is_active">Publicado (Visible en la tienda)</label>
            </div>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Guardar juego
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('title').addEventListener('input', function(e) {
    let title = e.target.value;
    let slug = title.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
    document.getElementById('slug').value = slug;
  });
</script>
@endsection
