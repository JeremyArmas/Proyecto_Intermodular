@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <div class="container py-5" style="max-width: 700px;">

        {{-- Alerta de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h1 class="fw-bold mb-4 text-white">Mi Perfil</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- FOTO DE PERFIL --}}
            <div class="mb-4 text-center d-block mx-auto"> <!-- d-block mx-auto hace que la imagen se centre -->
                @if(auth()->user()->avatar)
                    <img src="{{ asset('avatars/' . auth()->user()->avatar) }}" class="rounded-circle mb-2"
                        style="width:100px; height:100px; object-fit:cover; border: 3px solid #ffcc00; margin:0 auto">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2"
                        style="width:100px; height:100px; background:#ffcc00; font-size:2.5rem; font-weight:900; color:#000;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <label for="avatar" class="btn jg-btn jg-btn-outline btn-sm mt-1">
                        <i class="bi bi-camera me-1"></i> Cambiar foto
                    </label>
                    <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                </div>
                @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- NOMBRE --}}
            <div class="mb-3">
                <label class="form-label text-white">Nombre</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', auth()->user()->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- CORREO (solo lectura) --}}
            <div class="mb-3">
                <label class="form-label text-white">Correo electrónico</label>
                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
                <div class="form-text text-white">El correo no se puede cambiar.</div>
            </div>

            {{-- PAÍS --}}
            <div class="mb-3">
                <label class="form-label text-white">País</label>
                <select name="country" class="form-select">
                    <option value="">-- Selecciona tu país --</option>
                    <option value="España" {{ auth()->user()->country == 'España' ? 'selected' : '' }}>España</option>
                    <option value="Francia" {{ auth()->user()->country == 'Francia' ? 'selected' : '' }}>Francia</option>
                    <option value="Alemania" {{ auth()->user()->country == 'Alemania' ? 'selected' : '' }}>Alemania</option>
                    <option value="Italia" {{ auth()->user()->country == 'Italia' ? 'selected' : '' }}>Italia</option>
                    <option value="Portugal" {{ auth()->user()->country == 'Portugal' ? 'selected' : '' }}>Portugal</option>
                </select>
            </div>

            {{-- FECHA DE NACIMIENTO --}}
            <div class="mb-4">
                <label class="form-label text-white">Fecha de nacimiento</label>
                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                    value="{{ old('birth_date', auth()->user()->birth_date) }}">
                @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- BOTÓN GUARDAR --}}
            <button type="submit" class="btn jg-btn jg-btn-sun w-100">
                <i class="bi bi-save me-2"></i> Guardar cambios
            </button>

        </form>
    </div>
@endsection