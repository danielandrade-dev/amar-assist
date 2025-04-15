<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\ProductController;

Route::prefix ('/products')->name('products.')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{product:id|slug}/edit', 'edit')->name('edit');
    Route::put('/{product:id|slug}', 'update')->name('update');
    Route::delete('/{product:id|slug}', 'destroy')->name('destroy');
    Route::get('/{product:id|slug}', 'show')->name('show');
});
