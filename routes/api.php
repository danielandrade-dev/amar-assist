<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('products')->middleware('auth:sanctum')->group(function () {
    Route::get('/', function () {
        return new ProductCollection(Product::paginate(10));
    });

    Route::get('/{product:id|slug}', function (Product $product) {
        return new ProductResource($product);
    });

    Route::post('/', function (Request $request) {
        return new ProductResource(Product::create($request->all()));
    });

    Route::put('/{product:id|slug}', function (Request $request, Product $product) {
        $product->update($request->all());
        return new ProductResource($product);
    });

    Route::delete('/{product:id|slug}', function (Product $product) {
        $product->delete();
        return response()->json(null, 204);
    });
});

Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', function () {
        return new UserCollection(User::paginate(10));
    });

    Route::get('/{user:id|slug}', function (User $user) {
        return new UserResource($user);
    });

    Route::post('/', function (Request $request) {
        return new UserResource(User::create($request->all()));
    });

    Route::put('/{user:id|slug}', function (Request $request, User $user) {
        $user->update($request->all());
        return new UserResource($user);
    });

    Route::delete('/{user:id|slug}', function (User $user) {
        $user->delete();
        return response()->json(null, 204);
    });
});





