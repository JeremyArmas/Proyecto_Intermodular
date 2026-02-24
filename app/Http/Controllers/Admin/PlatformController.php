<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platforms = Platform::all();
        return view('admin.platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.platforms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:platforms,slug',
        ]);

        Platform::create($validated);

        return redirect()->route('admin.platforms.index')->with('success', 'Plataforma creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Platform $platform)
    {
        return view('admin.platforms.show', compact('platform')); // Optional
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:platforms,slug,' . $platform->id,
        ]);

        $platform->update($validated);

        return redirect()->route('admin.platforms.index')->with('success', 'Plataforma actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Platform $platform)
    {
        $platform->delete();
        return redirect()->route('admin.platforms.index')->with('success', 'Plataforma eliminada correctamente.');
    }
}
