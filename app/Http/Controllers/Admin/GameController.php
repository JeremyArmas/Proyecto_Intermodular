<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::with(['platform', 'categories'])->get();
        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $platforms = \App\Models\Platform::all();
        $categories = \App\Models\Category::all();
        return view('admin.games.create', compact('platforms', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
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
            'platform_id' => 'required|exists:platforms,id',
            'cover_image' => 'nullable|image|max:2048',
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
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return view('admin.games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        $platforms = \App\Models\Platform::all();
        $categories = \App\Models\Category::all();
        return view('admin.games.edit', compact('game', 'platforms', 'categories'));
    }

    /**
     * Update the specified resource in storage.
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
            'platform_id' => 'required|exists:platforms,id',
            'cover_image' => 'nullable|image|max:2048',
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
     * Remove the specified resource from storage.
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
