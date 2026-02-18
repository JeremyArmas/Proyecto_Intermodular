<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR CATEGORÍAS
        $categories = ['Acción', 'Aventura', 'RPG', 'Deportes', 'Estrategia', 'Shooter', 'Indie'];
        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = Category::create(['name' => $cat, 'slug' => Str::slug($cat)]);
        }

        // 2. CREAR PLATAFORMAS
        $platforms = [
            ['name' => 'PlayStation 5', 'slug' => 'ps5'],
            ['name' => 'Xbox Series X', 'slug' => 'xbox-series-x'],
            ['name' => 'PC', 'slug' => 'pc'],
            ['name' => 'Nintendo Switch', 'slug' => 'switch'],
        ];
        $platModels = [];
        foreach ($platforms as $plat) {
            $platModels[$plat['slug']] = Platform::create($plat);
        }

        // 3. CREAR USUARIOS
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gamezone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Cliente Normal
        User::create([
            'name' => 'Juan Cliente',
            'email' => 'cliente@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        // Empresa (B2B)
        $empresaUser = User::create([
            'name' => 'Tech Solutions SL', // Nombre del contacto o cuenta
            'email' => 'compras@techsolutions.com',
            'password' => Hash::make('password'),
            'role' => 'company',
        ]);
        
        // Perfil de la empresa
        CompanyProfile::create([
            'user_id' => $empresaUser->id,
            'company_name' => 'Tech Solutions S.L.',
            'tax_id' => 'B12345678',
            'phone' => '+34 912 345 678',
            'address' => 'Calle Industria 45, Madrid',
        ]);

        // 4. CREAR JUEGOS
        // Juego 1: Elden Ring (RPG en PS5)
        $game1 = Game::create([
            'title' => 'Elden Ring',
            'slug' => 'elden-ring-ps5',
            'description' => 'El aclamado RPG de acción desarrollado por FromSoftware.',
            'price' => 69.99,
            'b2b_price' => 45.00, // Precio para empresas
            'stock' => 50,
            'cover_image' => 'https://via.placeholder.com/300x400?text=Elden+Ring', // Imagen falsa por ahora
            'developer' => 'FromSoftware',
            'platform_id' => $platModels['ps5']->id,
        ]);
        // Asignar categorías (RPG y Acción) - Busca los IDs en el array que creamos arriba
        $game1->categories()->attach([1, 3]); // Asumiendo que 1 es Acción y 3 es RPG

        // Juego 2: Mario Kart (Deportes en Switch)
        $game2 = Game::create([
            'title' => 'Mario Kart 8 Deluxe',
            'slug' => 'mario-kart-8-switch',
            'description' => 'Carreras locas con los personajes de Mario.',
            'price' => 59.99,
            'b2b_price' => 40.00,
            'stock' => 100,
            'cover_image' => 'https://via.placeholder.com/300x400?text=Mario+Kart',
            'developer' => 'Nintendo',
            'platform_id' => $platModels['switch']->id,
        ]);
        $game2->categories()->attach([4]); // Deportes

        // Juego 3: Cyberpunk 2077 (PC)
        $game3 = Game::create([
            'title' => 'Cyberpunk 2077',
            'slug' => 'cyberpunk-2077-pc',
            'description' => 'Un RPG de mundo abierto ambientado en Night City.',
            'price' => 29.99,
            'b2b_price' => 20.00,
            'stock' => 200,
            'cover_image' => 'https://via.placeholder.com/300x400?text=Cyberpunk',
            'developer' => 'CD Projekt Red',
            'platform_id' => $platModels['pc']->id,
        ]);
        $game3->categories()->attach([1, 3, 6]); // Acción, RPG, Shooter
    }
}