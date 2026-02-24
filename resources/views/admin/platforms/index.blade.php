@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administrar Plataformas</h1>
    <a href="{{ route('admin.platforms.create') }}" class="btn btn-primary mb-3">Añadir Plataforma</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($platforms as $platform)
            <tr>
                <td>{{ $platform->id }}</td>
                <td>{{ $platform->name }}</td>
                <td>{{ $platform->slug }}</td>
                <td>
                    <a href="{{ route('admin.platforms.edit', $platform) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.platforms.destroy', $platform) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar plataforma?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
