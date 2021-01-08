<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/about-us', [\App\Http\Controllers\StaticPagesController::class, 'aboutUs'])
    ->name('about-us');
Route::get('/guidelines', [\App\Http\Controllers\StaticPagesController::class, 'guidelines'])
    ->name('guidelines');
Route::get('/legal-information', [\App\Http\Controllers\StaticPagesController::class, 'legalInformation'])
    ->name('legal');
Route::get('/photo-removal', [\App\Http\Controllers\StaticPagesController::class, 'photoRemoval'])
    ->name('photo-removal');
Route::get('/press-enquiries', [\App\Http\Controllers\StaticPagesController::class, 'pressEnquiries'])
    ->name('press-enquiries');
Route::get('/contact-us', \App\Http\Controllers\ContactUsController::class)
    ->name('contact-us');

Route::middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)
            ->name('dashboard');

        Route::get('/register', \App\Http\Controllers\RegisterUser::class)
            ->middleware('can: register users')
            ->name('register');
    });
