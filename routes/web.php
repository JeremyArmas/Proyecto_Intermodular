<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Ruta del home principal
Route::get('/', function () { return view('home'); });

//Rutas del login y logout
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Ruta a la vista del paneld el admin
Route::get('/admin', function(){ return view('adminPanel'); })->name('adminPanel');