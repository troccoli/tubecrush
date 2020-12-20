<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\HomeController::class)
    ->name('home');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', \App\Http\Controllers\DashboardController::class)
    ->name('dashboard');

Route::get('/register', \App\Http\Controllers\RegisterUser::class)
    ->middleware('can: register users')
    ->name('register');
