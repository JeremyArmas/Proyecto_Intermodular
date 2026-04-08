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
        $categories = [
            'Acción',
            'Aventura',
            'RPG',
            'Deportes',
            'Estrategia',
            'Shooter',
            'Indie',
            'Simulación',
            'Carreras',
            'Terror',
            'Mundo Abierto',
            'Multijugador',
            'Cooperativo',
            'Sandbox',
            'Pixel Art',
            'Puzzles',
            'Plataformas'
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = Category::firstOrCreate(['slug' => Str::slug($cat)], ['name' => $cat]);
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

        // Primer admin
        User::firstOrCreate(
            ['email' => 'admin@gamezone.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Segundo admin
        User::firstOrCreate(
            [
                'email' => 'jediga.s.a@gmail.com'
            ],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Cliente Normal
        User::firstOrCreate(
            ['email' => 'cliente@gmail.com'],
            [
                'name' => 'Juan Cliente',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        // Empresa (B2B)
        $empresaUser = User::firstOrCreate(
            ['email' => 'compras@techsolutions.com'],
            [
                'name' => 'Tech Solutions SL',
                'password' => Hash::make('password'),
                'role' => 'company',
            ]
        );

        // Perfil de la empresa
        CompanyProfile::firstOrCreate(
            ['user_id' => $empresaUser->id],
            [
                'company_name' => 'Tech Solutions S.L.',
                'tax_id' => 'B12345678',
                'phone' => '+34 912 345 678',
                'address' => 'Calle Industria 45, Madrid',
            ]
        );

        // 4. CREAR JUEGOS
        $games = [
            [
                'title' => 'Magrunner: Dark Pulse',
                'slug' => 'magrunner-dark-pulse-ps5',
                'description' => 'Un juego de puzles en primera persona ambientado en un universo post-apocalíptico donde la tecnología y la religión chocan.',
                'price' => 49.99,
                'b2b_price' => 35.00,
                'stock' => 75,
                'cover_image' => null,
                'developer' => 'Focus Home Interactive',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Puzzles', 'Acción', 'Aventura'],
            ],
            [
                'title' => 'HYPERCHARGE: Unboxed',
                'slug' => 'hypercharge-unboxed-xbox',
                'description' => '¡HYPERCHARGE! Es un shooter en primera persona de acción trepidante con un toque único: ¡eres un juguete! Defiende tu base de oleadas de enemigos mientras construyes y mejoras tus defensas. Juega solo o con amigos en modo cooperativo y enfréntate a desafíos cada vez mayores. ¡Prepárate para la batalla más épica que jamás hayas visto!',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 120,
                'cover_image' => null,
                'developer' => 'Digital Cybercherries',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Acción', 'Shooter', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Oddworld: Soulstorm',
                'slug' => 'oddworld-soulstorm-pc',
                'description' => 'Sumérgete en una aventura de plataformas y acción en 2.5D con una narrativa apasionante y gráficos impresionantes. Abe, un paria Mudokon, se embarca en una misión desesperada para liberar a sus congéneres de la esclavitud en una aventura llena de peligros, acertijos y acción trepidante.',
                'price' => 59.99,
                'b2b_price' => 42.00,
                'stock' => 60,
                'cover_image' => null,
                'developer' => 'Oddworld Inhabitants',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Plataformas', 'Puzzles'],
            ],
            [
                'title' => 'Knockout City',
                'slug' => 'knockout-city-xbox',
                'description' => 'Un juego de deportes multijugador donde los jugadores compiten en emocionantes partidas de dodgeball con power-ups y habilidades especiales.',
                'price' => 29.99,
                'b2b_price' => 20.00,
                'stock' => 150,
                'cover_image' => null,
                'developer' => 'Velan Studios',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Deportes', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Kena: Bridge of Spirits',
                'slug' => 'kena-bridge-of-spirits-ps5',
                'description' => 'Una aventura de acción y plataformas en tercera persona donde los jugadores controlan a Kena, una joven guía espiritual que viaja a un mundo abandonado para ayudar a los espíritus atrapados a encontrar la paz.',
                'price' => 11.99,
                'b2b_price' => 8.00,
                'stock' => 80,
                'cover_image' => null,
                'developer' => 'Ember Lab',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Aventura', 'Acción', 'Plataformas'],
            ],
            [
                'title' => 'CrossCode',
                'slug' => 'crosscode-xbox',
                'description' => 'Sumérgete en un cautivador RPG de acción con una jugabilidad trepidante y una historia que te atrapará desde el primer momento. CrossCode combina la acción frenética de los juegos de rol clásicos con mecánicas innovadoras y un mundo visualmente deslumbrante. ¡Prepárate para una aventura inolvidable!',
                'price' => 34.99,
                'b2b_price' => 25.00,
                'stock' => 90,
                'cover_image' => null,
                'developer' => 'Radical Fish Games',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Aventura', 'RPG', 'Puzzles', 'Pixel Art'],
            ],
            [
                'title' => 'Terraria',
                'slug' => 'terraria-pc',
                'description' => 'Un juego de acción y aventura en 2D con mecánicas únicas y un mundo procedural. ¡Crea, construye y lucha para sobrevivir en un mundo lleno de peligros y aventuras!',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 200,
                'cover_image' => null,
                'developer' => 'Re-Logic',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Indie', 'Mundo Abierto', 'Sandbox'], 
            ],
            [
                'title' => 'GTFO',
                'slug' => 'gtfo-pc',
                'description' => 'Un shooter cooperativo de terror y sigilo donde tú y tu equipo deben infiltrarse en una instalación subterránea abandonada y enfrentarse a hordas de criaturas aterradoras.',
                'price' => 54.99,
                'b2b_price' => 39.00,
                'stock' => 70,
                'cover_image' => null,
                'developer' => '10 Chambers',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Acción', 'Shooter', 'Terror', 'Multijugador'],
            ],
            [
                'title' => 'Skate',
                'slug' => 'skate-xbox',
                'description' => 'Un juego de skate en mundo abierto donde los jugadores pueden explorar una ciudad, realizar trucos y competir con otros jugadores.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 85,
                'cover_image' => null,
                'developer' => 'Full Circle',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Deportes', 'Mundo Abierto', 'Multijugador'],
            ],
            [
                'title' => 'Before Your Eyes',
                'slug' => 'before-your-eyes-pc',
                'description' => 'Un juego narrativo en primera persona que cuenta la historia de una vida a través de los ojos del protagonista. Un juego emotivo y conmovedor que te hará reflexionar sobre la vida y la muerte.',
                'price' => 36.99,
                'b2b_price' => 26.00,
                'stock' => 95,
                'cover_image' => null,
                'developer' => 'GoodbyeWorld Games',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Aventura', 'Indie'],
            ],
            [
                'title' => 'SkyDrift Infinity',
                'slug' => 'skydrift-infinity-switch',
                'description' => 'Un juego de carreras arcade con vehículos voladores personalizables y entornos espectaculares. Compite en carreras trepidantes alrededor del mundo, desbloquea nuevos vehículos y mejoras, y desafía a tus amigos en emocionantes modos multijugador.',
                'price' => 27.99,
                'b2b_price' => 20.00,
                'stock' => 130,
                'cover_image' => null,
                'developer' => 'Digital Reality',
                'platform_id' => $platModels['switch']->id,
                'categories' => ['Deportes', 'Carreras', 'Multijugador', 'Cooperativo'],
            ],
            [
                'title' => 'Remnant: From the Ashes',
                'slug' => 'remnant-from-the-ashes-ps5',
                'description' => 'Un shooter cooperativo de acción y aventura ambientado en un mundo postapocalíptico invadido por criaturas terroríficas. Los jugadores deben colaborar para sobrevivir, mejorar sus armas y habilidades, y enfrentarse a jefes desafiantes en una lucha desesperada por la humanidad.',
                'price' => 47.99,
                'b2b_price' => 34.00,
                'stock' => 65,
                'cover_image' => null,
                'developer' => 'Gunfire Games',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Acción', 'Terror', 'Mundo Abierto', 'Multijugador'],
            ],
            [
                'title' => 'Megabonk',
                'slug' => 'megabonk-pc',
                'description' => 'Un juego de lucha en primera persona que cuenta la historia de una vida a través de los ojos del protagonista. Un juego emotivo y conmovedor que te hará reflexionar sobre la vida y la muerte.',
                'price' => 22.99,
                'b2b_price' => 16.00,
                'stock' => 180,
                'cover_image' => null,
                'developer' => 'Megabonk',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Acción', 'Indie'],
            ],
            [
                'title' => 'Honkai: Star Rail',
                'slug' => 'honkai-star-rail-pc',
                'description' => 'Un juego de rol táctico gratuito ambientado en el universo de Honkai. Explora un mundo vibrante, domina el sistema de combate basado en turnos y forja tu destino en una aventura que evoluciona con la comunidad.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => null,
                'developer' => 'miHoYo',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['RPG', 'Aventura'],
            ],
            [
                'title'=> 'Rain World',
                'slug' => 'rain-world-pc',
                'description' => 'Rain World es un juego de plataformas y supervivencia enfocado en la exploración, ambientado en un mundo abandonado hace mucho tiempo e invadido por criaturas tanto fascinantes como aterradoras. Una lluvia intensa y devastadora golpea regularmente la superficie, haciendo que la vida, tal y como la conocemos, sea casi imposible. Las criaturas de este mundo hibernan gran parte del tiempo, pero deben pasar los períodos secos entre lluvias buscando comida para sobrevivir un día más.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => null,
                'developer' => 'Videocult',
                'platform_id' => $platModels['pc']->id,
                'categories' => ['Acción', 'Aventura', 'Indie'],
            ],
            [
                'title'=> 'Firewatch',
                'slug' => 'firewatch-xbox',
                'description' => 'Firewatch es un juego de misterio y aventura en primera persona ambientado en el bosque nacional de Shoshone, Wyoming, en 1989. El jugador asume el papel de Henry, un hombre que acepta un trabajo como vigilante de incendios para escapar de los problemas de su vida personal. A medida que se adentra en la naturaleza salvaje, Henry establece una relación por radio con Delilah, su supervisora, y juntos descubren un misterio que amenaza con desentrañar sus vidas.',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 100,
                'cover_image' => null,
                'developer' => 'Campo Santo',
                'platform_id' => $platModels['xbox-series-x']->id,
                'categories' => ['Aventura', 'Indie'],
            ],
            [
                'title' => 'PowerWash Simulator',
                'slug' => 'powerwash-simulator-switch',
                'description' => '¡Enciende la hidrolimpiadora y deja todo reluciente en este simulador de limpieza! PowerWash Simulator te permite limpiar desde pequeños objetos hasta enormes edificios con una variedad de herramientas y accesorios. Disfruta de una experiencia relajante y satisfactoria mientras transformas la suciedad en brillo.',
                'price' => 19.99,
                'b2b_price' => 14.00,
                'stock' => 100,
                'cover_image' => null,
                'developer' => 'FuturLab',
                'platform_id' => $platModels['switch']->id,
                'categories' => ['Simulación'],
            ],
            [
                'title' => 'Where Winds Meet',
                'slug' => 'where-winds-meet-ps5',
                'description' => 'Where Winds Meet es un juego de rol de acción ambientado en la China de finales de la dinastía Qing. El jugador asume el papel de un joven artista marcial que debe elegir entre unirse a una secta secreta de asesinos o a un grupo de rebeldes que luchan por la libertad. El juego cuenta con un sistema de combate basado en artes marciales, un mundo abierto para explorar y una historia ramificada con múltiples finales.',
                'price' => 0,
                'b2b_price' => 0,
                'stock' => 100,
                'cover_image' => null,
                'developer' => 'Everstone Studio',
                'platform_id' => $platModels['ps5']->id,
                'categories' => ['Acción', 'Aventura', 'RPG'],
            ]
        ];

        // Elimina juegos cuyo slug ya no aparece en el seeder.
        // Así el catálogo queda sincronizado con el array de arriba.
        $seederSlugs = array_column($games, 'slug');
        Game::whereNotIn('slug', $seederSlugs)->each(function (Game $game) {
            $game->categories()->detach();
            $game->delete();
        });

        foreach ($games as $gameData) {

            // firstOrCreate: crea el juego solo si no existe aún.
            // Si ya existe (aunque el admin haya cambiado datos), lo deja intacto.
            $game = Game::firstOrCreate(
                ['slug' => $gameData['slug']],
                [
                    'title' => $gameData['title'],
                    'description' => $gameData['description'],
                    'price' => $gameData['price'],
                    'b2b_price' => $gameData['b2b_price'],
                    'stock' => $gameData['stock'],
                    'cover_image' => $gameData['cover_image'],
                    'developer' => $gameData['developer'],
                    'platform_id' => $gameData['platform_id'],
                ]
            );

            // Asocia las categorías al juego (asegurando que existan)
            $categoryIds = Category::whereIn('name', $gameData['categories'])->pluck('id')->toArray();
            $game->categories()->sync($categoryIds);
        }
    }
}