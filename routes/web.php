<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/',         [PageController::class, 'home'])->name('home');
Route::get('/menu',     [PageController::class, 'menu'])->name('menu');
Route::get('/about',    [PageController::class, 'about'])->name('about');
Route::get('/login',    [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::get('/account',  [PageController::class, 'account'])->name('account');
Route::get('/profile',  [PageController::class, 'profile'])->name('profile');

// Catch-all so client-side routes (e.g. deep links) still resolve
Route::get('/{any}', [PageController::class, 'spa'])->where('any', '.*');
