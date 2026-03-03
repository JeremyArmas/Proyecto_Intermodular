@extends('layouts.app')

@section('title', 'Editar Usuario • Administración')

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
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Editar Usuario: {{ $user->name }}</h1>
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
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label text-white">Nombre</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <div class="mb-3">
          <label for="email" class="form-label text-white">Email</label>
          <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
        </div>

        <div class="mb-4">
          <label for="role" class="form-label text-white">Rol</label>
          <select class="form-select" id="role" name="role" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Cliente B2C</option>
            <option value="company" {{ old('role', $user->role) == 'company' ? 'selected' : '' }}>Empresa B2B</option>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
        </div>

        <hr style="border-color: rgba(255,255,255,0.1);">
        <h5 class="text-white mb-3">Cambiar contraseña (opcional)</h5>
        <div class="form-text jg-muted mb-3">Deja estos campos en blanco si no quieres cambiar la contraseña actual del usuario.</div>

        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <label for="password" class="form-label text-white">Nueva Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
          <div class="col-md-6">
            <label for="password_confirmation" class="form-label text-white">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Actualizar usuario
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
