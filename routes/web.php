<?php

use Illuminate\Support\Facades\Route;
use App\Models\News;

//Web Controllers
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\AuthController as WebAuthController;
use App\Http\Controllers\Web\GameController as WebGameController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\CurrencyController;
use App\Http\Controllers\Web\WishlistController;

//Admin Controllers
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\AdministratorController;


//Ruta del home principal
Route::get('/', [HomeController::class, 'index'])->name('home');

//Ruta a sobre nosotros
Route::get('/sobre-nosotros', function() {return view('sobre-nosotros'); });

//Ruta a noticias
Route::get('/noticias', function() {
    $noticias = News::where('is_published', true)->orderBy('created_at','desc')->paginate(10);
    return view('noticias', compact('noticias'));
})->name('noticias.public');

Route::get('/noticias/{id}', function($id) {
    $news = News::where('is_published', true)->findOrFail($id);
    return view('noticias_show', compact('news'));
})->name('noticias.show.public');


//Ruta a soporte
Route::get('/soporte', function() {return view('soporte'); });

//Ruta del gestión de cuentas (accecible desde el soporte)
Route::get('/soporte/gestion-cuentas', function() {return view('gestion-cuentas'); })-> name('soporte.gestion-cuentas');

//Ruta de Técnico/Juegos (accecible desde el soporte)
Route::get('/soporte/tecnico-juegos', function() {return view('tecnico-juegos'); })-> name('soporte.tecnico-juegos');

//Ruta a FAQ
Route::get('/faq', function() {return view('faq'); });

//Ruta a contacto
Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
Route::post('/contacto', [ContactController::class, 'store'])->name('contacto.store');

//Rutas del login y logout
Route::get('/login', function () {return redirect()->route('home');})->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.submit');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

//Ruta del captcha a la hora de refrescar
Route::get('/reload-captcha', function () {return response()->json(['captcha' => captcha_img('flat'),]);})->name('captcha.reload');

// Rutas del Carrito (Página completa) - Requiere Autenticación
Route::middleware('auth')->prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Rutas de Checkout (Stripe)
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/session', [CheckoutController::class, 'createSession'])->name('session');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
});

// Ruta de Cambio de Moneda
Route::get('/moneda/{code}', [CurrencyController::class, 'switch'])->name('currency.switch');

// Rutas del Perfil de Usuario
Route::middleware('auth:web,admin')->prefix('perfil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/mis-pedidos', [ProfileController::class, 'orders'])->name('orders');
    Route::post('/mis-pedidos/{id}/confirmar', [ProfileController::class, 'confirmDelivery'])->name('orders.confirm');
    Route::get('/mis-pedidos/{id}/descargar', [ProfileController::class, 'downloadOrderPdf'])->name('orders.download'); //Descargar la factura en pdf
    Route::get('/biblioteca', [ProfileController::class, 'biblioteca'])->name('biblioteca'); //Mi biblioteca de juegos
});

// Rutas de la Wishlist (Lista de Deseos)
Route::middleware('auth:web,admin')->name('wishlist.')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('index');
    Route::post('/wishlist/add', [WishlistController::class, 'store'])->name('store');
    Route::delete('/wishlist/remove/{wishlist}', [WishlistController::class, 'destroy'])->name('destroy');
    Route::post('/wishlist/move-to-cart/{wishlist}', [WishlistController::class, 'moveToCart'])->name('moveToCart');
});

// Rutas de Juegos y Catálogo
Route::get('/catalogo', [WebGameController::class, 'index'])->name('catalogo');
Route::get('/juego/{slug}', [WebGameController::class, 'show'])->name('juego.show');

//Grupo de Rutas de Administración (Protegidas por el nuevo guard admin)
Route::middleware(['auth:admin', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Vista del panel principal
    Route::get('/', [AdminController::class, 'index'])->name('panel');
    
    // CRUDs con permisos granulares
    
    // Juegos
    Route::middleware('permission:games.view')->get('games', [GameController::class, 'index'])->name('games.index');
    Route::middleware('permission:games.create')->group(function() {
        Route::get('games/create', [GameController::class, 'create'])->name('games.create');
        Route::post('games', [GameController::class, 'store'])->name('games.store');
    });
    Route::middleware('permission:games.view')->get('games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::middleware('permission:games.update')->group(function() {
        Route::get('games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
        Route::put('games/{game}', [GameController::class, 'update'])->name('games.update');
    });
    Route::middleware('permission:games.delete')->delete('games/{game}', [GameController::class, 'destroy'])->name('games.destroy');

    // Categorías
    Route::middleware('permission:categories.view')->get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::middleware('permission:categories.create')->group(function() {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    });
    Route::middleware('permission:categories.view')->get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::middleware('permission:categories.update')->group(function() {
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    });
    Route::middleware('permission:categories.delete')->delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Plataformas
    Route::middleware('permission:platforms.view')->get('platforms', [PlatformController::class, 'index'])->name('platforms.index');
    Route::middleware('permission:platforms.create')->group(function() {
        Route::get('platforms/create', [PlatformController::class, 'create'])->name('platforms.create');
        Route::post('platforms', [PlatformController::class, 'store'])->name('platforms.store');
    });
    Route::middleware('permission:platforms.view')->get('platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');
    Route::middleware('permission:platforms.update')->group(function() {
        Route::get('platforms/{platform}/edit', [PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('platforms/{platform}', [PlatformController::class, 'update'])->name('platforms.update');
    });
    Route::middleware('permission:platforms.delete')->delete('platforms/{platform}', [PlatformController::class, 'destroy'])->name('platforms.destroy');

    // Usuarios (Clientes)
    Route::middleware('permission:users.view')->get('users', [UserController::class, 'index'])->name('users.index');
    Route::middleware('permission:users.create')->group(function() {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });
    Route::middleware('permission:users.view')->get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::middleware('permission:users.update')->group(function() {
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    });
    Route::middleware('permission:users.delete')->delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Pedidos (Órdenes) con permisos granulares
    Route::middleware('permission:orders.view')->group(function() {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{id}/descargar', [OrderController::class, 'downloadOrderPdf'])->name('orders.download');
        Route::get('orders/download-all', [OrderController::class, 'exportAllPdfs'])->name('orders.download-all');
    });

    Route::middleware('permission:orders.update')->group(function() {
        Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    });

    Route::middleware('permission:orders.delete')->delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Noticias con permisos granulares
    Route::middleware('permission:news.view')->group(function() {
        Route::get('news', [NewsController::class, 'index'])->name('news.index');
        Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
    });
    Route::middleware('permission:news.create')->group(function() {
        Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
        Route::post('news', [NewsController::class, 'store'])->name('news.store');
    });
    Route::middleware('permission:news.update')->group(function() {
        Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
        Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
    });
    Route::middleware('permission:news.delete')->delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

    // Tickets con permisos granulares
    Route::middleware('permission:tickets.view')->group(function() {
        Route::get('tickets', [AdminContactController::class, 'index'])->name('tickets.index');
        Route::get('tickets/{ticket}', [AdminContactController::class, 'show'])->name('tickets.show');
    });
    Route::middleware('permission:tickets.update')->put('tickets/{ticket}', [AdminContactController::class, 'update'])->name('tickets.update');

    // Gestión de Administradores (Solo Super Admin)
    Route::middleware('can:manage-administrators')->group(function() {
        Route::resource('administrators', AdministratorController::class);
    });

});

//Rutas de recuperación de contraseña
Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', function ($token) { return view('auth.reset-password', ['token' => $token]); })->middleware('guest')->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('guest')->name('password.update');

//Ruta a Aviso Legal
Route::get('/aviso-legal', function () { return view('aviso-legal'); })->name('aviso-legal');

//Ruta a terminos y condiciones
Route::get('/terminos', function () { return view('terminos'); })->name('terminos');

//Ruta a cookies
Route::get('/cookies', function () { return view('cookies'); })->name('cookies');