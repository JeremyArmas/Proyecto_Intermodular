@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Administrar Juegos</h1>
    <a href="{{ route('admin.games.create') }}" class="btn btn-primary mb-3">Añadir Juego</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Plataformas</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
            <tr>
                <td>{{ $game->id }}</td>
                <td>{{ $game->title }}</td>
                <td>
                    @foreach($game->categories as $category)
                        <span class="badge bg-secondary">{{ $category->name }}</span>
                    @endforeach
                </td>
                <td>{{ $game->platform ? $game->platform->name : 'N/A' }}</td>
                <td>${{ number_format($game->price, 2) }}</td>
                <td>
                    <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('admin.games.destroy', $game) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar juego?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
