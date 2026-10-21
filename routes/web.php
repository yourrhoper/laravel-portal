<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;


Route::get('', function () {
    return view('welcome');
});

Route::get('/admin', [MainController::class, 'index'])
    ->name('admin.index'); // куда переходить, когда в строке /admin

Route::prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
});