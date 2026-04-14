<?php

use Illuminate\Support\Facades\Route;
use App\Models\News;

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
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\NewsController;


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

// Rutas del Perfil de Usuario
use App\Http\Controllers\Web\ProfileController;
Route::middleware('auth')->prefix('perfil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/mis-pedidos', [ProfileController::class, 'orders'])->name('orders');
    Route::get('/mis-pedidos/{id}/descargar', [ProfileController::class, 'downloadOrderPdf'])->name('orders.download'); //Descargar la factura en pdf
});

// Rutas de Juegos y Catálogo
use App\Http\Controllers\Web\GameController as WebGameController;
Route::get('/catalogo', [WebGameController::class, 'index'])->name('catalogo');
Route::get('/juego/{slug}', [WebGameController::class, 'show'])->name('juego.show');

//Grupo de Rutas de Administración (Protegidas por Auth en un futuro)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Vista del panel principal
    Route::get('/', [AdminController::class, 'index'])->name('panel');
    
    // CRUDs
    Route::get('/orders/{id}/download', [OrderController::class, 'downloadOrderPdf'])->name('orders.download'); // Descargar la factura en pdf
    Route::get('/orders/download-all', [OrderController::class, 'exportAllPdfs'])->name('orders.download-all'); // Descargar todas las facturas en pdf
    Route::resource('games', GameController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('platforms', PlatformController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('news', NewsController::class); // CRUD de noticias
    Route::resource('tickets', AdminContactController::class)->only(['index', 'show', 'update']);
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