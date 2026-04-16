<?php

use Illuminate\Support\Facades\Route;

//Web Controllers
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\AuthController as WebAuthController;

//Admin Controllers
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Auth\PasswordResetController;


//Ruta del home principal
Route::get('/', [HomeController::class, 'index'])->name('home');

//Ruta a sobre nosotros
Route::get('/sobre-nosotros', function() {return view('sobre-nosotros'); });

//Ruta a noticias
Route::get('/noticias', function() {return view('noticias'); });

//Ruta a soporte
Route::get('/soporte', function() {return view('soporte'); });

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
use App\Http\Controllers\Web\CartController;
Route::middleware('auth')->prefix('carrito')->name('carrito.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
});

// Rutas de Checkout (Stripe)
use App\Http\Controllers\Web\CheckoutController;
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::post('/session', [CheckoutController::class, 'createSession'])->name('session');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
});

// Ruta de Cambio de Moneda
use App\Http\Controllers\Web\CurrencyController;
Route::get('/moneda/{code}', [CurrencyController::class, 'switch'])->name('currency.switch');

// Rutas del Perfil de Usuario
use App\Http\Controllers\Web\ProfileController;
Route::middleware('auth')->prefix('perfil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/mis-pedidos', [ProfileController::class, 'orders'])->name('orders');
});

// Rutas de Juegos y Catálogo
use App\Http\Controllers\Web\GameController as WebGameController;
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

    Route::resource('orders', OrderController::class);
    Route::resource('tickets', AdminContactController::class)->only(['index', 'show', 'update']);
    Route::resource('administrators', AdministratorController::class);
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