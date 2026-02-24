@extends('layouts.app')

@section('title', 'Usuarios • Administración')

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
          <h1 class="jg-section-title h3 mb-2" style="display:block;">Usuarios</h1>
          <div class="jg-muted">Gestión de roles y estado de clientes.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <a href="{{ route('admin.users.create') }}" class="btn jg-btn jg-btn-primary">
            <i class="bi bi-person-plus me-1"></i> Invitar/Crear
          </a>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success mt-3" style="background-color: var(--jg-mint-fade); border-color: var(--jg-mint); color: #fff;">
        {{ session('success') }}
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
                <th>Rol</th>
                <th>Fecha alta</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $u)
                @php
                  // 'client', 'company', 'admin'
                  $bRol = $u->role === 'admin' ? 'badge-sun' : ($u->role === 'company' ? 'badge-soft' : 'badge-mint');
                @endphp
                <tr>
                  <td>#{{ $u->id }}</td>
                  <td class="fw-bold">{{ $u->name }}</td>
                  <td>{{ $u->email }}</td>
                  <td><span class="badge {{ $bRol }}">{{ ucfirst($u->role) }}</span></td>
                  <td class="text-nowrap">{{ $u->created_at->format('Y-m-d') }}</td>
                  <td class="text-end text-nowrap">
                    <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm jg-btn jg-btn-outline"><i class="bi bi-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4 jg-muted">No hay usuarios en el sistema.</td>
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
