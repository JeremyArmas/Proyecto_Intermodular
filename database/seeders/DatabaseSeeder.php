<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Game;
use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. CARGAR ADMINISTRADORES Y PERMISOS
        $this->call(AdministratorSeeder::class);

        // 1. CREAR CATEGORÍAS
        $categories = [
            'Acción', 'Aventura', 'RPG', 'Deportes', 'Estrategia',
            'Shooter', 'Indie', 'Simulación', 'Carreras', 'Terror',
            'Mundo Abierto', 'Multijugador', 'Cooperativo', 'Sandbox',
            'Pixel Art', 'Puzzles', 'Plataformas'
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => Str::slug($cat)], ['name' => $cat]);
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
            $platModels[$plat['slug']] = Platform::firstOrCreate(['slug' => $plat['slug']], ['name' => $plat['name']]);
        }

        // 3. CREAR USUARIOS
        User::firstOrCreate(['email' => 'admin@gamezone.com'], [
            'name' => 'Administrador',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'jediga.s.a@gmail.com'], [
            'name' => 'Administrador Jediga',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'cliente@gmail.com'], [
            'name' => 'Juan Cliente',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        $empresaUser = User::firstOrCreate(['email' => 'compras@techsolutions.com'], [
            'name' => 'Tech Solutions SL',
            'password' => Hash::make('password'),
            'role' => 'company',
        ]);

        CompanyProfile::firstOrCreate(['user_id' => $empresaUser->id], [
            'company_name' => 'Tech Solutions S.L.',
            'tax_id' => 'B12345678',
            'phone' => '+34 912 345 678',
            'address' => 'Calle Industria 45, Madrid',
        ]);
        // 4. CREAR JUEGOS (Catálogo Premium de Jere)
        $games = [
            [
                'title' => 'Magrunner: Dark Pulse',
                'slug' => 'magrunner-dark-pulse-ps5',
                'description' => 'Un juego de puzles en primera persona ambientado en un universo post-apocalíptico donde la tecnología y la religión chocan.',
                'price' => 49.99,
                'b2b_price' => 35.00,
                'stock' => 75,
                'cover_image' => 'games/magrunner-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=liis3oT6t5M',
                'developer' => 'Focus Home Interactive',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Puzzles', 'Acción', 'Aventura'],
            ],
            [
                'title' => 'HYPERCHARGE: Unboxed',
                'slug' => 'hypercharge-unboxed-xbox',
                'description' => '¡HYPERCHARGE! Es un shooter en primera persona de acción trepidante con un toque único: ¡eres un juguete! Defiende tu base de oleadas de enemigos mientras construyes y mejoras tus defensas.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 120,
                'cover_image' => 'games/hypercharge-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=Yp69I5yWp0Y',
                'developer' => 'Digital Cybercherries',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Acción', 'Shooter', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Oddworld: Soulstorm',
                'slug' => 'oddworld-soulstorm-pc',
                'description' => 'Sumérgete en una aventura de plataformas y acción en 2.5D con una narrativa apasionante y gráficos impresionantes.',
                'price' => 59.99,
                'b2b_price' => 42.00,
                'stock' => 60,
                'cover_image' => 'games/oddworld-soulstorm-cover.png',
                'trailer_url' => 'https://www.youtube.com/watch?v=F9b-_jCCBhg',
                'developer' => 'Oddworld Inhabitants',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Plataformas', 'Puzzles'],
            ],
            [
                'title' => 'Knockout City',
                'slug' => 'knockout-city-xbox',
                'description' => 'Un juego de deportes multijugador donde los jugadores compiten en emocionantes partidas de dodgeball.',
                'price' => 29.99,
                'b2b_price' => 20.00,
                'stock' => 150,
                'cover_image' => 'games/knockout-city-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=DJVyyojmY1E',
                'release_date' => '2026-04-26',
                'developer' => 'Velan Studios',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Deportes', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Kena: Bridge of Spirits',
                'slug' => 'kena-bridge-of-spirits-ps5',
                'description' => 'Una aventura de acción y plataformas en tercera persona donde los jugadores controlan a Kena, una joven guía espiritual.',
                'price' => 11.99,
                'b2b_price' => 8.00,
                'stock' => 80,
                'cover_image' => 'games/kena-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=OxzWlIbnp3U',
                'developer' => 'Ember Lab',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Aventura', 'Acción', 'Plataformas'],
            ],
            [
                'title' => 'CrossCode',
                'slug' => 'crosscode-xbox',
                'description' => 'Sumérgete en un cautivador RPG de acción con una jugabilidad trepidante y una historia que te atrapará.',
                'price' => 34.99,
                'b2b_price' => 25.00,
                'stock' => 90,
                'cover_image' => 'games/crosscode-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=8pZJ8U7lU90',
                'developer' => 'Radical Fish Games',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Aventura', 'RPG', 'Puzzles', 'Pixel Art'],
            ],
            [
                'title' => 'Terraria',
                'slug' => 'terraria-pc',
                'description' => 'Un juego de acción y aventura en 2D con mecánicas únicas y un mundo procedural.',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 200,
                'cover_image' => 'games/terraria-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=w7uOhFTrgu0',
                'developer' => 'Re-Logic',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Indie', 'Mundo Abierto', 'Sandbox'],
            ],
            [
                'title' => 'GTFO',
                'slug' => 'gtfo-pc',
                'description' => 'Un shooter cooperativo de terror y sigilo donde tú y tu equipo deben infiltrarse en una instalación subterránea.',
                'price' => 54.99,
                'b2b_price' => 39.00,
                'stock' => 70,
                'cover_image' => 'games/gtfo-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=7uU_n_9p1f0',
                'developer' => '10 Chambers',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Acción', 'Shooter', 'Terror', 'Multijugador'],
            ],
            [
                'title' => 'Skate',
                'slug' => 'skate-xbox',
                'description' => 'Un juego de skate en mundo abierto donde los jugadores pueden explorar una ciudad y realizar trucos.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 85,
                'cover_image' => 'games/skate-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=0hD6p8X9Okg',
                'developer' => 'Full Circle',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Deportes', 'Mundo Abierto', 'Multijugador'],
            ],
            [
                'title' => 'Before Your Eyes',
                'slug' => 'before-your-eyes-pc',
                'description' => 'Un juego narrativo en primera persona que cuenta la historia de una vida a través de los ojos del protagonista.',
                'price' => 36.99,
                'b2b_price' => 26.00,
                'stock' => 95,
                'cover_image' => 'games/before-your-eyes-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=0eD6p8X9Okg',
                'developer' => 'GoodbyeWorld Games',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Indie'],
            ],
            [
                'title' => 'SkyDrift Infinity',
                'slug' => 'skydrift-infinity-switch',
                'description' => 'Un juego de carreras arcade con vehículos voladores personalizables y entornos espectaculares.',
                'price' => 27.99,
                'b2b_price' => 20.00,
                'stock' => 130,
                'cover_image' => 'games/skydrift-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=0fD6p8X9Okg',
                'developer' => 'Digital Reality',
                'platform_id' => $platModels['switch']->id,
                'categories' => ['Deportes', 'Carreras', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Remnant: From the Ashes',
                'slug' => 'remnant-from-the-ashes-ps5',
                'description' => 'Un shooter cooperativo de acción y aventura ambientado en un mundo postapocalíptico invadido por criaturas terroríficas.',
                'price' => 47.99,
                'b2b_price' => 34.00,
                'stock' => 65,
                'cover_image' => 'games/remnant-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=emLEsdK19PE',
                'developer' => 'Gunfire Games',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Acción', 'Terror', 'Mundo Abierto', 'Multijugador'],
            ],
            [
                'title' => 'Megabonk',
                'slug' => 'megabonk-pc',
                'description' => 'Un juego de lucha en primera persona emotivo y conmovedor.',
                'price' => 22.99,
                'b2b_price' => 16.00,
                'stock' => 180,
                'cover_image' => 'games/megabonk-cover.png',
                'trailer_url' => 'https://www.youtube.com/watch?v=eMICNfeKWZ4',
                'release_date' => '2026-12-05',
                'developer' => 'Megabonk',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Acción', 'Indie'],
            ],
            [
                'title' => 'Honkai: Star Rail',
                'slug' => 'honkai-star-rail-pc',
                'description' => 'Un juego de rol táctico gratuito ambientado en el universo de Honkai.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => 'games/honkai-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=w8vPZrMFiZ4',
                'developer' => 'miHoYo',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['RPG', 'Aventura'],
            ],
            [
                'title'=> 'Rain World',
                'slug' => 'rain-world-switch',
                'description' => 'Rain World es un juego de plataformas y supervivencia enfocado en la exploración.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => 'games/rain-world-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=8vEHyYl1IrA',
                'developer' => 'Videocult',
                'platform_id' => $platModels['switch']->id,
                'categories' => ['Acción', 'Aventura', 'Indie'],
            ],
            [
                'title'=> 'Firewatch',
                'slug' => 'firewatch-xbox',
                'description' => 'Firewatch es un juego de misterio y aventura en primera persona.',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 100,
                'cover_image' => 'games/firewatch-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=d02lhvvVSy8',
                'developer' => 'Campo Santo',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Aventura', 'Indie'],
            ],
            [
                'title' => 'PowerWash Simulator',
                'slug' => 'powerwash-simulator-switch',
                'description' => '¡Enciende la hidrolimpiadora y deja todo reluciente!',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 100,
                'cover_image' => 'games/powerwash-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=TkJl36xzQpw',
                'developer' => 'FuturLab',
                'platform_id' => $platModels['switch']->id,
                'categories' => ['Simulación'],
            ],
            [
                'title' => 'Where Winds Meet',
                'slug' => 'where-winds-meet-ps5',
                'description' => 'Where Winds Meet es un juego de rol de acción ambientado en la China Qing.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => 'games/wherewindsmeet-cover.jpg',
                'trailer_url' => 'https://www.youtube.com/watch?v=NhgJ7UFubTc',
                'release_date' => '2026-05-11',
                'developer' => 'Everstone Studio',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Acción', 'Aventura', 'RPG'],
            ]
        ];

        // 5. CREAR NOTICIAS
        $noticias = [
            [
            'title' => 'Sección de Noticias',
            'content' => 'Dentro de poco podrás encontrar aquí las últimas noticias sobre videojuegos.',
            'image' => '../images/logo_jediga_provisional.png',
            'is_published' => true,
            ]
        ];

        //Busca si ya existe una noticia con ese título y, si no la encuentra, vuelca todos los datos del array directamente para crearla de golpe.
        foreach ($noticias as $n) { 
            News::firstOrCreate(['title' => $n['title']], $n);
        }

        // Elimina juegos cuyo slug ya no aparece en el seeder.
        // Así el catálogo queda sincronizado con el array de arriba.
        $seederSlugs = array_column($games, 'slug');
        Game::whereNotIn('slug', $seederSlugs)->each(function (Game $game) {
            $game->categories()->detach();
            $game->delete();
        });

        foreach ($games as $gameData) {
            $game = Game::updateOrCreate(
                ['slug' => $gameData['slug']],
                [
                    'title' => $gameData['title'],
                    'description' => $gameData['description'],
                    'price' => $gameData['price'],
                    'b2b_price' => $gameData['b2b_price'],
                    'stock' => $gameData['stock'],
                    'cover_image' => $gameData['cover_image'],
                    'trailer_url' => $gameData['trailer_url'] ?? null,
                    'release_date' => $gameData['release_date'] ?? null,
                    'developer' => $gameData['developer'],
                    'platform_id' => $gameData['platform_id'],
                ]
            );

            $categoryIds = Category::whereIn('name', $gameData['categories'])->pluck('id')->toArray();
            $game->categories()->sync($categoryIds);
        }
    }
}