<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Datos “mock” para los juegos destacados en el home. En un futuro, esto vendría de la base de datos.
        $upcoming = [
            ['title'=>'Astra Runner','desc'=>'Acción / Roguelite','tag'=>'PS','slug'=>'astra-runner'],
            ['title'=>'Neon Rift: Season 1','desc'=>'Competitivo 4v4','tag'=>'PC','slug'=>'neon-rift-season-1'],
            ['title'=>'Skyforge Lite','desc'=>'Aventura ligera','tag'=>'Switch','slug'=>'skyforge-lite'],
        ];

        $popular = [
            ['title'=>'Neon Rift','desc'=>'Shooter táctico','tag'=>'PC','slug'=>'neon-rift'],
            ['title'=>'Pulse Arena','desc'=>'Arena rápido','tag'=>'PC','slug'=>'pulse-arena'],
            ['title'=>'Echoes DLC','desc'=>'Contenido extra','tag'=>'PC','slug'=>'echoes-dlc'],
        ];

        $free = [
            ['title'=>'Zero Byte','desc'=>'Indie corto','tag'=>'Xbox','slug'=>'zero-byte'],
            ['title'=>'Starter Pack','desc'=>'Pack de bienvenida','tag'=>'PC','slug'=>'starter-pack'],
            ['title'=>'Trial Access','desc'=>'Acceso limitado','tag'=>'PS','slug'=>'trial-access'],
        ];

        // Slides del hero del home, con datos “mock” para mostrar la estructura. En un futuro, esto también vendría de la base de datos o de una configuración.
        $heroSlides = [
            [
                'pill' => 'Próximamente',
                'desc2' => 'Descubre lo que viene en camino y guarda tus favoritos.',
                'badgeClass' => 'badge-soft',
                'badgeText' => 'Soon',
                'game' => $upcoming[0],
                'mediaType' => 'video',
                'mediaSrc' => 'videos/hero.mp4',
                'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$upcoming[0]['slug']), 'class'=>'jg-btn-sun'],
                'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?status=upcoming'), 'class'=>'jg-btn-primary'],
                'tertiary' => ['text'=>'Catálogo', 'href'=> url('/catalogo'), 'class'=>'jg-btn-outline'],
            ],
            [
                'pill' => 'Más populares',
                'desc2' => 'Lo más jugado ahora mismo. Entra a la ficha o mira el top completo.',
                'badgeClass' => 'badge-sun',
                'badgeText' => 'Top',
                'game' => $popular[0],
                'mediaType' => 'video',
                'mediaSrc' => 'videos/hero3.mp4',
                'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$popular[0]['slug']), 'class'=>'jg-btn-sun'],
                'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?sort=popular'), 'class'=>'jg-btn-primary'],
                'tertiary' => ['text'=>'Catálogo', 'href'=> url('/catalogo'), 'class'=>'jg-btn-outline'],
            ],
            [
                'pill' => 'Gratis',
                'desc2' => 'Entra sin pagar: juegos y packs para empezar rápido.',
                'badgeClass' => 'badge-mint',
                'badgeText' => 'Free',
                'game' => $free[0],
                'mediaType' => 'video',
                'mediaSrc' => 'videos/hero2.mp4',
                'primary' => ['text'=>'Ver ficha', 'href'=> url('/juego/'.$free[0]['slug']), 'class'=>'jg-btn-sun'],
                'secondary' => ['text'=>'Ver todos', 'href'=> url('/catalogo?price=free'), 'class'=>'jg-btn-primary'],
                'tertiary' => ['text'=>'Catálogo', 'href'=> url('/catalogo'), 'class'=>'jg-btn-outline'],
            ],
        ];

        return view('home', compact('upcoming', 'popular', 'free', 'heroSlides'));
    }
}
