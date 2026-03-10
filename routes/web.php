<?php

use Illuminate\Support\Facades\Route;

//Web Controllers
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AuthController as WebAuthController;

//Admin Controllers
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;

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
Route::get('/contacto', function() {return view('contacto'); });

//Rutas del login y logout
Route::post('/login', [WebAuthController::class, 'login'])->name('login');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

//Ruta del captcha a la hora de refrescar
Route::get('/reload-captcha', function () {return response()->json(['captcha' => captcha_img('flat'),]);})->name('captcha.reload');

//Grupo de Rutas de Administración (Protegidas por Auth en un futuro)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Vista del panel principal
    Route::get('/', [AdminController::class, 'index'])->name('panel');
    
    // CRUDs
    Route::resource('games', GameController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('platforms', PlatformController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
});