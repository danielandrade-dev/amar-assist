<?php
declare(strict_types=1);

namespace App\Http\Controllers\web;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\UserController;

Route::prefix('/users')->name('users.')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/{user:id|email}/edit', 'edit')->name('edit');
    Route::put('/{user:id|email}', 'update')->name('update');
    Route::delete('/{user:id|email}', 'destroy')->name('destroy');
});
