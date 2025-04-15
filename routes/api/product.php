<?php
declare(strict_types=1);

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;


Route::prefix('products')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/', function (Request $request) {

        $perPage = $request->input('per_page') ?? 15;
        $page = $request->input('page') ?? 1;
        $products = Product::query()
            ->with('activeStorage')
            ->search($request->all())
            ->paginate($perPage, ['*'], 'page', $page);
        return new ProductCollection($products);
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