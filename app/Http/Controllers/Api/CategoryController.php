<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\DTO\CategoryData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //Muestra una lista de categorías con paginación
    public function index(Request $request)
    {
        // Validación de parámetros de paginación, muestran 10 categorías por página por defecto
        $perPage = (int) $request->query('per_page', 10);
        $page = (int) $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        // Consulta SQL para obtener las categorías con paginación
        $categorias = DB::select(
            'SELECT id, name, slug, created_at, updated_at
             FROM categories
             ORDER BY id DESC
             LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );

        // Consulta SQL para obtener el total de categorías
        $totalCategorias = DB::selectOne(
            'SELECT COUNT(*) as total FROM categories'
        )->total;

        // Respuesta JSON con los datos y la información de paginación
        return response()->json([
            'data' => $categorias,
            'meta' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => (int) $totalCategorias,
                'total_pages' => (int) ceil($totalCategorias / $perPage),
            ]
        ], 200);
    }

    // Muestra los detalles de una categoría específica por su ID
    public function show(int $id)
    {

    // Consulta SQL para obtener la categoría por su ID
        $categoria = DB::selectOne(
            'SELECT id, name, slug, created_at, updated_at
             FROM categories
             WHERE id = ?',
            [$id]
        );

        // Si no se encuentra la categoría, devuelve un error 404
        if (!$categoria) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Respuesta JSON con los datos de la categoría encontrada
        return response()->json([
            'data' => $categoria
        ], 200);
    }

    // Crea una nueva categoría con validación de datos
    public function store(Request $request)
    {

        // Validación de los datos de entrada para crear una nueva categoría
        $datosValidados = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80'],
        ]);

        // Generación del slug a partir del nombre o del slug proporcionado, asegurando su unicidad
        $name = trim($datosValidados['name']);
        $slug = !empty($datosValidados['slug'])
            ? Str::slug($datosValidados['slug'])
            : Str::slug($name);

        // Verificación de la unicidad del slug y generación de uno nuevo si ya existe
        $slugBase = $slug;
        $i = 2;

        // Consulta SQL para verificar si el slug ya existe en la base de datos
        while (DB::selectOne(
            'SELECT 1 FROM categories WHERE slug = ? LIMIT 1',
            [$slug]
        )) {
            $slug = $slugBase . '-' . $i;
            $i++;
        }

        // Creación de un objeto DTO para la categoría y inserción en la base de datos
        $dto = new CategoryData($name, $slug);

        // Consulta SQL para insertar la nueva categoría en la base de datos
        DB::insert(
            'INSERT INTO categories (name, slug, created_at, updated_at)
             VALUES (?, ?, NOW(), NOW())',
            [$dto->name, $dto->slug]
        );

        // Obtención del ID de la categoría recién creada y consulta para obtener sus datos completos
        $id = (int) DB::getPdo()->lastInsertId();

        // Consulta SQL para obtener los datos de la categoría recién creada
        $categoriaCreada = DB::selectOne(
            'SELECT id, name, slug, created_at, updated_at
             FROM categories
             WHERE id = ?',
            [$id]
        );

        // Respuesta JSON con un mensaje de éxito y los datos de la categoría creada
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $categoriaCreada
        ], 201);
    }

    // Actualiza una categoría existente por su ID con validación de datos
    public function update(Request $request, int $id)
    {

        // Consulta SQL para verificar si la categoría existe antes de intentar actualizarla
        $CategoriaExistente = DB::selectOne(
            'SELECT id, name, slug, created_at, updated_at
             FROM categories
             WHERE id = ?',
            [$id]
        );

        // Si la categoría no existe, devuelve un error 404
        if (!$CategoriaExistente) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Validación de los datos de entrada para actualizar la categoría
        $datosValidados = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80'],
        ]);

        // Generación del slug a partir del nombre o del slug proporcionado, asegurando su unicidad
        $name = trim($datosValidados['name']);
        $slug = !empty($datosValidados['slug'])
            ? Str::slug($datosValidados['slug'])
            : Str::slug($name);

        // Verificación de la unicidad del slug y generación de uno nuevo si ya existe, excluyendo la categoría actual
        $slugBase = $slug;
        $i = 2;

        // Consulta SQL para verificar si el slug ya existe en la base de datos, excluyendo la categoría actual
        while (DB::selectOne(
            'SELECT 1 FROM categories WHERE slug = ? AND id <> ? LIMIT 1',
            [$slug, $id]
        )) {
            $slug = $slugBase . '-' . $i;
            $i++;
        }

        // Creación de un objeto DTO para la categoría y actualización en la base de datos
        $dto = new CategoryData($name, $slug);

        // Consulta SQL para actualizar la categoría en la base de datos
        DB::update(
            'UPDATE categories
             SET name = ?, slug = ?, updated_at = NOW()
             WHERE id = ?',
            [$dto->name, $dto->slug, $id]
        );

        // Consulta SQL para obtener los datos de la categoría actualizada
        $categoriaActualizada = DB::selectOne(
            'SELECT id, name, slug, created_at, updated_at
             FROM categories
             WHERE id = ?',
            [$id]
        );

        // Respuesta JSON con un mensaje de éxito y los datos de la categoría actualizada
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $categoriaActualizada
        ], 200);
    }

    // Elimina una categoría por su ID
    public function destroy(int $id)
    {

        // Consulta SQL para eliminar la categoría por su ID    
        $categoriaEliminada = DB::delete(
            'DELETE FROM categories WHERE id = ?',
            [$id]
        );

        // Si no se eliminó ninguna fila, significa que la categoría no fue encontrada
        if (!$categoriaEliminada) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // Respuesta JSON con un mensaje de éxito indicando que la categoría fue eliminada
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
