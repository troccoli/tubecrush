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

Route::view('/', 'home')->name('home');
Route::view('/about-us', 'static.about-us')->name('about-us');
Route::view('/guidelines', 'static.guidelines')->name('guidelines');
Route::view('/legal-information', 'static.legal-information')->name('legal');
Route::view('/photo-removal', 'static.photo-removal')->name('photo-removal');
Route::view('/press-enquiries', 'static.press-enquiries')->name('press-enquiries');
Route::view('/contact-us', 'static.contact-us')->name('contact-us');
Route::get('/lines/{slug}', \App\Http\Controllers\PostsByLinesController::class)
    ->name('posts-by-lines');
Route::get('/tag/{slug}', \App\Http\Controllers\PostsByTagsController::class)
    ->name('posts-by-tags');
Route::get('/post/{post:slug}', \App\Http\Controllers\SinglePostController::class)
    ->name('single-post');
Route::view('/send-crush', 'send-crush')->name('send-crush');
Route::view('/send-crush/thank-you', 'static.send-crush-success')->name('send-crush-success');

Route::middleware(['auth:sanctum', 'verified'])
    ->group(function () {
        Route::view('/dashboard', 'dashboard.index')->name('dashboard');

        Route::view('/register', 'dashboard.register-user')
            ->middleware('can:register users')
            ->name('register');

        Route::view('/posts', 'dashboard.posts.list')
            ->middleware('can:view posts')
            ->name('posts.list');

        Route::view('/posts/create', 'dashboard.posts.create')
            ->middleware('can:create posts')
            ->name('posts.create');

        Route::view('/posts/update/{postId}', 'dashboard.posts.update')
            ->middleware('can:update posts')
            ->name('posts.update');
    });
