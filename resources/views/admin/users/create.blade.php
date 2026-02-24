@extends('layouts.app')

@section('title', 'Añadir Usuario • Administración')

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
          <h1 class="jg-section-title h3 mb-0" style="display:block;">Añadir Usuario</h1>
        </div>
        <div>
          <a href="{{ route('admin.users.index') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
        </div>
      </div>
    </div>

    <div class="jg-card p-4">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label text-white">Nombre</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label text-white">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="role" class="form-label text-white">Rol</label>
          <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
            <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Cliente B2C</option>
            <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>Empresa B2B</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
          </select>
          @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row mb-4">
          <div class="col-md-6 mb-3 mb-md-0">
            <label for="password" class="form-label text-white">Contraseña</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label for="password_confirmation" class="form-label text-white">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #fff;">
          </div>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn jg-btn jg-btn-primary">
            Guardar usuario
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
