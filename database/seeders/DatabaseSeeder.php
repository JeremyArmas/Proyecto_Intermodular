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
        $games = [
            [
                'title' => 'Shadow Realm Chronicles',
                'slug' => 'shadow-realm-chronicles-ps5',
                'description' => 'Embárcate en una aventura épica a través de reinos sombríos llenos de misterios y criaturas míticas.',
                'price' => 49.99,
                'b2b_price' => 35.00,
                'stock' => 75,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Shadow+Realm+Chronicles',
                'developer' => 'Mystic Studios',
                'platform_id' => $platModels['ps5']->id,
                'categories' => [1, 2], // Acción, Aventura
            ],
            [
                'title' => 'Neon Blitz Arena',
                'slug' => 'neon-blitz-arena-xbox',
                'description' => 'Un shooter frenético en un mundo cyberpunk donde los reflejos son tu mejor arma.',
                'price' => 39.99,
                'b2b_price' => 28.00,
                'stock' => 120,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Neon+Blitz+Arena',
                'developer' => 'Pixel Fury Games',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => [6], // Shooter
            ],
            [
                'title' => 'Echoes of Eternity',
                'slug' => 'echoes-of-eternity-pc',
                'description' => 'Un RPG profundo donde tus elecciones moldean el destino de mundos paralelos.',
                'price' => 59.99,
                'b2b_price' => 42.00,
                'stock' => 60,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Echoes+of+Eternity',
                'developer' => 'Infinite Realms',
                'platform_id' => $platModels['pc']->id,
                'categories' => [3], // RPG
            ],
            [
                'title' => 'Jungle Dash Heroes',
                'slug' => 'jungle-dash-heroes-switch',
                'description' => 'Carreras locas a través de junglas exóticas con héroes únicos y power-ups salvajes.',
                'price' => 29.99,
                'b2b_price' => 20.00,
                'stock' => 150,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Jungle+Dash+Heroes',
                'developer' => 'Tropical Games',
                'platform_id' => $platModels['switch']->id,
                'categories' => [4], // Deportes
            ],
            [
                'title' => 'Galactic Conquest',
                'slug' => 'galactic-conquest-ps5',
                'description' => 'Construye imperios galácticos en esta estrategia espacial de gran escala.',
                'price' => 44.99,
                'b2b_price' => 32.00,
                'stock' => 80,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Galactic+Conquest',
                'developer' => 'Star Forge Studios',
                'platform_id' => $platModels['ps5']->id,
                'categories' => [5], // Estrategia
            ],
            [
                'title' => 'Mystic Isles Adventure',
                'slug' => 'mystic-isles-adventure-xbox',
                'description' => 'Explora islas encantadas llenas de magia y peligros en esta aventura narrativa.',
                'price' => 34.99,
                'b2b_price' => 25.00,
                'stock' => 90,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Mystic+Isles+Adventure',
                'developer' => 'Enchanted Worlds',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => [2], // Aventura
            ],
            [
                'title' => 'Pixel Quest Legends',
                'slug' => 'pixel-quest-legends-pc',
                'description' => 'Un indie RPG pixelado con mecánicas únicas y un mundo procedural infinito.',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 200,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Pixel+Quest+Legends',
                'developer' => 'Retro Pixel Co.',
                'platform_id' => $platModels['pc']->id,
                'categories' => [3, 7], // RPG, Indie
            ],
            [
                'title' => 'Aqua Racing Championship',
                'slug' => 'aqua-racing-championship-switch',
                'description' => 'Compite en carreras acuáticas con vehículos submarinos en pistas oceánicas.',
                'price' => 24.99,
                'b2b_price' => 18.00,
                'stock' => 110,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Aqua+Racing+Championship',
                'developer' => 'Oceanic Sports',
                'platform_id' => $platModels['switch']->id,
                'categories' => [4], // Deportes
            ],
            [
                'title' => 'Void Hunter Elite',
                'slug' => 'void-hunter-elite-ps5',
                'description' => 'Caza criaturas del vacío en este shooter de supervivencia en el espacio.',
                'price' => 54.99,
                'b2b_price' => 39.00,
                'stock' => 70,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Void+Hunter+Elite',
                'developer' => 'Cosmic Hunters',
                'platform_id' => $platModels['ps5']->id,
                'categories' => [1, 6], // Acción, Shooter
            ],
            [
                'title' => 'Kingdom Builder Saga',
                'slug' => 'kingdom-builder-saga-xbox',
                'description' => 'Construye y defiende tu reino en esta estrategia medieval épica.',
                'price' => 41.99,
                'b2b_price' => 30.00,
                'stock' => 85,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Kingdom+Builder+Saga',
                'developer' => 'Medieval Masters',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => [5], // Estrategia
            ],
            [
                'title' => 'Dream Weaver Chronicles',
                'slug' => 'dream-weaver-chronicles-pc',
                'description' => 'Teje sueños y pesadillas en este RPG surrealista con mecánicas innovadoras.',
                'price' => 36.99,
                'b2b_price' => 26.00,
                'stock' => 95,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Dream+Weaver+Chronicles',
                'developer' => 'Surreal Studios',
                'platform_id' => $platModels['pc']->id,
                'categories' => [3, 7], // RPG, Indie
            ],
            [
                'title' => 'Skybound Racers',
                'slug' => 'skybound-racers-switch',
                'description' => 'Carreras aéreas en cielos infinitos con aviones personalizables y turbulencias extremas.',
                'price' => 27.99,
                'b2b_price' => 20.00,
                'stock' => 130,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Skybound+Racers',
                'developer' => 'Aerial Adventures',
                'platform_id' => $platModels['switch']->id,
                'categories' => [4], // Deportes
            ],
            [
                'title' => 'Infernal Realms',
                'slug' => 'infernal-realms-ps5',
                'description' => 'Desciende a los reinos infernales en esta aventura de acción oscura y terrorífica.',
                'price' => 47.99,
                'b2b_price' => 34.00,
                'stock' => 65,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Infernal+Realms',
                'developer' => 'Dark Abyss Games',
                'platform_id' => $platModels['ps5']->id,
                'categories' => [1, 2], // Acción, Aventura
            ],
            [
                'title' => 'Quantum Chess Masters',
                'slug' => 'quantum-chess-masters-xbox',
                'description' => 'Un twist cuántico al ajedrez clásico con mecánicas de estrategia multidimensional.',
                'price' => 22.99,
                'b2b_price' => 16.00,
                'stock' => 180,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Quantum+Chess+Masters',
                'developer' => 'Quantum Minds',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => [5], // Estrategia
            ],
            [
                'title' => 'Lunar Colony Simulator',
                'slug' => 'lunar-colony-simulator-pc',
                'description' => 'Simula y gestiona una colonia lunar en este indie de estrategia y supervivencia.',
                'price' => 31.99,
                'b2b_price' => 23.00,
                'stock' => 100,
                'cover_image' => 'https://via.placeholder.com/300x400?text=Lunar+Colony+Simulator',
                'developer' => 'Space Pioneers',
                'platform_id' => $platModels['pc']->id,
                'categories' => [5, 7], // Estrategia, Indie
            ],
        ];

        foreach ($games as $gameData) {
            $game = Game::create([
                'title' => $gameData['title'],
                'slug' => $gameData['slug'],
                'description' => $gameData['description'],
                'price' => $gameData['price'],
                'b2b_price' => $gameData['b2b_price'],
                'stock' => $gameData['stock'],
                'cover_image' => $gameData['cover_image'],
                'developer' => $gameData['developer'],
                'platform_id' => $gameData['platform_id'],
            ]);
            $game->categories()->attach($gameData['categories']);
        }
    }
}