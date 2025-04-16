<?php
declare(strict_types=1);

namespace App\Http\Controllers\web;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\ProductController;

Route::prefix ('/products')->name('products.')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{slug}/edit', 'edit')->name('edit');
    Route::put('/{slug}', 'update')->name('update');
    Route::delete('/{product:slug}', 'destroy')->name('destroy');
    Route::get('/{slug}', 'show')->name('show');
    Route::put('/{product:slug}/status', 'changeStatus')->name('changeStatus');
})->middleware('throttle:60,1');
