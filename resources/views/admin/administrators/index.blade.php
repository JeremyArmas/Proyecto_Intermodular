@extends('layouts.app')

@section('title', 'Administradores • Administración')

@section('content')

<div class="jg-admin jg-admin-wrap">
  <div class="container">
    <div class="jg-admin-header p-4 mb-4">
      <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
          <div class="jg-pill mb-2">
            <span class="jg-dot"></span>
            <span>Panel de administración</span>
          </div>
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Administradores</h1>
          <div class="jg-muted">Gestión de administradores y sus privilegios específicos.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.panel') }}" class="btn jg-btn jg-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
          </a>
          <a href="{{ route('admin.administrators.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-person-plus me-1"></i> Crear Admin
          </a>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-3" style="background-color: var(--jg-mint-fade); border-color: var(--jg-mint); color: #fff;">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger mt-3">
        {{ session('error') }}
      </div>
    @endif

    <div class="jg-card p-3 mb-3">
      <div class="jg-table-wrap">
        <div class="table-responsive">
          <table class="table jg-table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Permisos</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($administrators as $admin)
                <tr>
                  <td>#{{ $admin->id }}</td>
                  <td class="fw-bold">{{ $admin->name }}</td>
                  <td>{{ $admin->email }}</td>
                  <td>
                    @if($admin->is_super_admin)
                      <span class="badge badge-sun">Super Admin</span>
                    @else
                      <span class="badge badge-soft">Admin</span>
                    @endif
                  </td>
                  <td>
                    @if($admin->is_super_admin)
                      <span class="jg-muted">Poder absoluto</span>
                    @else
                      @foreach($admin->permissions as $perm)
                        <span class="badge badge-mint" style="font-size: 0.7rem;">{{ $perm->name }}</span>
                      @endforeach
                    @endif
                  </td>
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.administrators.edit', $admin) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.administrators.destroy', $admin) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este administrador?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4 jg-muted">No hay administradores registrados.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
