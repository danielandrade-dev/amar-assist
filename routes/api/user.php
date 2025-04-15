<?php
declare(strict_types=1);

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;


Route::prefix('users')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/', function () {
        return new UserCollection(User::paginate());
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