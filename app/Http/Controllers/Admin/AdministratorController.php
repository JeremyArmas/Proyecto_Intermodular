<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdministratorController extends Controller
{
    /**
     * Muestra la lista de administradores. Solo accesible para Super Admin.
     */
    public function index()
    {
        $this->checkSuperAdmin();
        $administrators = Administrator::with('permissions')->get();
        return view('admin.administrators.index', compact('administrators'));
    }

    /**
     * Muestra el formulario para crear un nuevo administrador.
     */
    public function create()
    {
        $this->checkSuperAdmin();
        $permissions = Permission::all();
        return view('admin.administrators.create', compact('permissions'));
    }

    /**
     * Almacena un nuevo administrador en la base de datos.
     */
    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:administrators,email',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'array',
            'is_super_admin' => 'boolean',
        ]);

        $admin = Administrator::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_super_admin' => $request->boolean('is_super_admin'),
        ]);

        if ($request->has('permissions')) {
            $admin->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.administrators.index')->with('success', 'Administrador creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un administrador.
     */
    public function edit(Administrator $administrator)
    {
        $this->checkSuperAdmin();
        $permissions = Permission::all();
        return view('admin.administrators.edit', compact('administrator', 'permissions'));
    }

    /**
     * Actualiza un administrador en la base de datos.
     */
    public function update(Request $request, Administrator $administrator)
    {
        $this->checkSuperAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:administrators,email,' . $administrator->id,
            'permissions' => 'array',
            'is_super_admin' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $administrator->password = Hash::make($request->password);
        }

        $administrator->name = $validated['name'];
        $administrator->email = $validated['email'];
        $administrator->is_super_admin = $request->boolean('is_super_admin');
        $administrator->save();

        if ($request->has('permissions')) {
            $administrator->permissions()->sync($request->permissions);
        } else {
            $administrator->permissions()->detach();
        }

        return redirect()->route('admin.administrators.index')->with('success', 'Administrador actualizado correctamente.');
    }

    /**
     * Elimina un administrador.
     */
    public function destroy(Administrator $administrator)
    {
        $this->checkSuperAdmin();

        // El Super Admin puede eliminar a quien quiera (incluyéndose a sí mismo si lo desea, según requerimiento)
        $administrator->delete();

        return redirect()->route('admin.administrators.index')->with('success', 'Administrador eliminado correctamente.');
    }

    /**
     * Método privado para verificar si el usuario es Super Admin.
     */
    private function checkSuperAdmin()
    {
        if (!Auth::guard('admin')->user()->is_super_admin) {
            abort(403, 'No tienes permiso para gestionar administradores.');
        }
    }
}
