<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Ruta del home principal
Route::get('/', function () { return view('home'); });

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


use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;

//Rutas del login y logout
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Grupo de Rutas de Administración (Protegidas por Auth en un futuro)
Route::prefix('admin')->name('admin.')->group(function () {
    // Vista del panel principal
    Route::get('/', function(){ return view('adminPanel'); })->name('panel');
    
    // CRUDs
    Route::resource('games', GameController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('platforms', PlatformController::class);
    Route::resource('users', UserController::class);
    Route::resource('orders', OrderController::class);
});