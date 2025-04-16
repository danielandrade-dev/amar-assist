<?php
declare(strict_types=1);

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::prefix('products')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/', [ProductController::class, 'index']);

    Route::get('/{product:id|slug}', [ProductController::class, 'show']);

    Route::post('/', [ProductController::class, 'store']);

    Route::put('/{product:id|slug}', [ProductController::class, 'update']);

    Route::delete('/{product:id|slug}', [ProductController::class, 'destroy']);
});