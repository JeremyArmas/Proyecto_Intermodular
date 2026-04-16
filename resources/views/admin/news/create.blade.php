@extends('layouts.app')

@section('title', 'Crear Noticia • Jediga')

@section('content')
<div class="container py-4">
    <h2>Añadir Nueva Noticia</h2>
    <div class="jg-card p-4 mt-3">
        
        @php
            $canCreate = auth('admin')->user()->hasPermission('news.create');
        @endphp
        
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Campo Título -->
            <div class="mb-3">
                <label class="form-label">Título *</label>
                <input type="text" name="title" class="form-control" required value="{{ old('title') }}" {{ !$canCreate ? 'disabled' : '' }}>
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Campo Imagen -->
            <div class="mb-3">
                <label class="form-label">Imagen Destacada *</label>
                <input type="file" name="image" class="form-control" accept="image/*" required {{ !$canCreate ? 'disabled' : '' }}>
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                <small class="d-block mt-2"><i class="bi bi-info-circle"></i> Tamaño recomendado: 1920x1080. Solo se permiten los formatos jpeg,png,jpg,gif y el tamaño máximo es de 2MB.</small>
            </div>

            <!-- Campo Contenido -->
            <div class="mb-3">
                <label class="form-label">Cuerpo de la Noticia *</label>
                <textarea name="content" class="form-control" rows="6" required {{ !$canCreate ? 'disabled' : '' }}>{{ old('content') }}</textarea>
                @error('content') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <!-- Switch de publicación -->
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_published" id="publishedSwitch" checked {{ !$canCreate ? 'disabled' : '' }}>
                <label class="form-check-label" for="publishedSwitch">Publicar inmediatamente</label>
            </div>

            @if($canCreate)
                <button type="submit" class="btn jg-btn jg-btn-primary">Guardar Noticia</button>
            @endif
            <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline ms-2">Cancelar</a>
        </form>


    </div>
</div>
@endsection