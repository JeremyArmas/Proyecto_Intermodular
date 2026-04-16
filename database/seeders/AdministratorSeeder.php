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
        // 1. Crear el Super Admin
        Administrator::updateOrCreate(
            ['email' => 'diego2004@gmail.com'],
            [
                'name' => 'Diego SuperAdmin',
                'password' => Hash::make('holaadios'),
                'is_super_admin' => true,
            ]
        );

        // 2. Definir Permisos CRUD
        $entities = ['games', 'categories', 'platforms', 'users', 'orders', 'tickets'];
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
    }
}
