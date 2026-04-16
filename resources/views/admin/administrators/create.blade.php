@extends('layouts.app')

@section('title', 'Crear Administrador • Administración')

@section('content')

<div class="jg-admin jg-admin-wrap">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="jg-admin-header p-4 mb-4">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
              <div class="jg-pill mb-2">
                <span class="jg-dot"></span>
                <span>Panel de administración</span>
              </div>
              <h1 class="jg-section-title h3 mb-2" style="display:block;">Crear Nuevo Administrador</h1>
              <div class="jg-muted">Define los accesos y privilegios del nuevo miembro.</div>
            </div>
            <a href="{{ route('admin.administrators.index') }}" class="btn jg-btn jg-btn-outline">
              <i class="bi bi-arrow-left me-1"></i> Volver a la lista
            </a>
          </div>
        </div>
      </div>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="jg-card p-4">
          <form action="{{ route('admin.administrators.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
              <div class="col-md-6 mb-3">
                <label class="form-label jg-muted">Nombre completo</label>
                <input type="text" name="name" class="form-control jg-input" value="{{ old('name') }}" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label jg-muted">Email</label>
                <input type="email" name="email" class="form-control jg-input" value="{{ old('email') }}" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6 mb-3">
                <label class="form-label jg-muted">Contraseña</label>
                <input type="password" name="password" class="form-control jg-input" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label jg-muted">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control jg-input" required>
              </div>
            </div>

            <div class="form-check form-switch mb-4">
              <input class="form-check-input" type="checkbox" name="is_super_admin" value="1" id="isSuperAdminSwitch">
              <label class="form-check-label fw-bold" for="isSuperAdminSwitch">Es Super Administrador (Poder total)</label>
            </div>

            <div id="permissionsSection">
              <h5 class="mb-3 jg-section-title" style="display:block; font-size: 1.1rem;">Privilegios Específicos</h5>
              <div class="jg-muted mb-3 small">Selecciona solo las acciones que este administrador puede realizar.</div>
              
              <div class="row">
                @foreach($permissions as $permission)
                  <div class="col-md-4 mb-2">
                    <div class="form-check">
                      <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                      <label class="form-check-label small" for="perm_{{ $permission->id }}">
                        {{ $permission->name }}
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
              <button type="submit" class="btn jg-btn jg-btn-primary px-5">
                <i class="bi bi-save me-1"></i> Crear Administrador
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('isSuperAdminSwitch').addEventListener('change', function() {
    const permissionsSection = document.getElementById('permissionsSection');
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    
    if (this.checked) {
      permissionsSection.style.opacity = '0.5';
      permissionsSection.style.pointerEvents = 'none';
      checkboxes.forEach(c => c.checked = false);
    } else {
      permissionsSection.style.opacity = '1';
      permissionsSection.style.pointerEvents = 'auto';
    }
  });
</script>

@endsection
