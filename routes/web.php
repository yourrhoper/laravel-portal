<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// регистрация
Route::get('/register', [UserController::class, 'create'])->name('register.create');
Route::post('/register', [UserController::class, 'store'])->name('register.store');

Route::prefix('admin')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('admin.index');
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
    Route::resource('posts', PostController::class);
});
