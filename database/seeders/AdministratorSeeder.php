<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrator;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definir los roles (Super Admin o Admin normal)
        $admins = [
            ['email' => 'diego2004@gmail.com', 'name' => 'Diego SuperAdmin', 'password' => 'holaadios', 'super' => true],
            ['email' => 'admin@gamezone.com', 'name' => 'Administrador', 'password' => 'password', 'super' => true],
            ['email' => 'jediga.s.a@gmail.com', 'name' => 'Administrador Jediga', 'password' => 'password', 'super' => false],
        ];

        // 2. Definir Permisos CRUD
        $entities = ['games', 'categories', 'platforms', 'users', 'orders', 'tickets', 'news'];
        $actions = [
            'view' => 'Ver',
            'create' => 'Crear',
            'update' => 'Editar',
            'delete' => 'Borrar'
        ];

        foreach ($entities as $entity) {
            foreach ($actions as $actionSlug => $actionName) {
                Permission::updateOrCreate(
                    ['slug' => "{$entity}.{$actionSlug}"],
                    ['name' => "{$actionName} " . ucfirst($entity)]
                );
            }
        }

        // 3. Crear los administradores y asignar permisos
        foreach ($admins as $adminData) {
            $admin = Administrator::updateOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => Hash::make($adminData['password']),
                    'is_super_admin' => $adminData['super'],
                ]
            );

            // Si no es super admin, le asignamos todos los permisos EXCEPTO borrar
            if (!$adminData['super']) {
                $permissions = Permission::where('slug', 'not like', '%.delete')->pluck('id');
                $admin->permissions()->sync($permissions);
            }
        }
    }
}
