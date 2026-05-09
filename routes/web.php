<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/about', [AboutController::class, 'index'])->name('about');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.forgot');
Route::get('/reset-password', [AuthController::class, 'showReset'])->name('password.reset');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.customer')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/profile', [AccountController::class, 'edit'])->name('profile');
    Route::post('/profile', [AccountController::class, 'update']);
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/order-confirmation/{id}', [AccountController::class, 'confirmation'])->name('order.confirmation');
});
