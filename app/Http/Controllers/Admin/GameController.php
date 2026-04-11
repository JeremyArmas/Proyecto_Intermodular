<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Muestra un listado del recurso.
     */
    public function index()
    {
        $games = Game::with(['platform', 'categories'])->get();
        return view('admin.games.index', compact('games'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $platforms = \App\Models\Platform::all();
        $categories = \App\Models\Category::all();
        return view('admin.games.create', compact('platforms', 'categories'));
    }

    /**
     * Almacena un recurso recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:games,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'b2b_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'developer' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'platform_id' => 'required|exists:platforms,id',
            'cover_image' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('games', 'public');
            $validated['cover_image'] = $path;
        }

        $game = Game::create($validated);

        if (isset($validated['categories'])) {
            $game->categories()->sync($validated['categories']);
        }

        return redirect()->route('admin.games.index')->with('success', 'Juego creado correctamente.');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(Game $game)
    {
        return view('admin.games.show', compact('game'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(Game $game)
    {
        $platforms = \App\Models\Platform::all();
        $categories = \App\Models\Category::all();
        return view('admin.games.edit', compact('game', 'platforms', 'categories'));
    }

    /**
     * Actualiza el recurso especificado en la base de datos.
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:games,slug,' . $game->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'b2b_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'developer' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'platform_id' => 'required|exists:platforms,id',
            'cover_image' => 'nullable|image|max:2048',
            'trailer_url' => 'nullable|url|max:255',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            if ($game->cover_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($game->cover_image);
            }
            $path = $request->file('cover_image')->store('games', 'public');
            $validated['cover_image'] = $path;
        }

        $game->update($validated);

        if (isset($validated['categories'])) {
            $game->categories()->sync($validated['categories']);
        } else {
            $game->categories()->sync([]);
        }

        return redirect()->route('admin.games.index')->with('success', 'Juego actualizado correctamente.');
    }

    /**
     * Elimina el recurso especificado de la base de datos.
     */
    public function destroy(Game $game)
    {
        if ($game->cover_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($game->cover_image);
        }
        $game->categories()->detach();
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Juego eliminado correctamente.');
    }
}
