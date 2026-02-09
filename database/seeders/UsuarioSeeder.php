<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeder del admin
        Usuario::create(
            ['email' => 'Admin@demo.com', 'nombre' => 'Admin', 'contraseña' => Hash::make('Admin1234'), 'es_admin' => true]
        );
    }
}
