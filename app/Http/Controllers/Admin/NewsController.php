<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('admin.panel');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=> 'required|string|max:255',
            'content'=> 'required|string',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $rutaImagen = $request->file('image')->store('news', 'public');

        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->image = $rutaImagen;
        $news->is_published = $request->has('is_published');
        $news->save();
        return redirect()->route('admin.panel')->with('success', 'Noticia creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::findOrFail($id);
        return view('noticias_show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=> 'required|string|max:255',
            'content'=> 'required|string',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $news = News::findOrFail($id);
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        if ($request->hasFile('image')) { //Si se sube una imagen nueva
            $imagenVieja =$news->image;
            if ($imagenVieja && str_starts_with($imagenVieja, 'news/') && !News::where('image', $imagenVieja)->where('id', '!=', $id)->first()) { //Si la imagen existe y no es usada por otra noticia
                Storage::disk('public')->delete($imagenVieja); //Se elimina la imagen vieja.
            }

            //Se que Laravel ignora completamente el nombre original del archivo y le genera un nombre aleatorio único (un UUID).
            //Por lo tanto, no necesito preocuparme por colisiones de nombres.
            //Sin embargo, para evitar errores, he decidido añadir una comprobación de que la imagen existe y no es usada por otra noticia.
            //Si la imagen existe y no es usada por otra noticia, se elimina la imagen vieja.
            //Si la imagen no existe o es usada por otra noticia, se guarda la nueva imagen.

        $news->image = $request->file('image')->store('news', 'public'); //Se guarda la nueva imagen
        }
        $news->is_published = $request->has('is_published');
        $news->save();
        return redirect()->route('admin.panel')->with('success', 'Noticia actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        if ($news->image && str_starts_with($news->image, 'news/') && !News::where('image', $news->image)->where('id', '!=', $id)->first()) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();
        return redirect()->route('admin.panel')->with('success', 'Noticia eliminada correctamente');
    }
}
