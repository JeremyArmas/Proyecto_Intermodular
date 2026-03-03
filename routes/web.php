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


//Rutas del login y logout
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


//Ruta a la vista del panel del admin
Route::get('/admin', function(){ return view('adminPanel'); })->name('adminPanel');

//Ruta del captcha a la hora de refrescar
Route::get('/reload-captcha', function () {return response()->json(['captcha' => captcha_img('flat'),]);})->name('captcha.reload');