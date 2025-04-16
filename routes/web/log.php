<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\ProductLog;
use Illuminate\Http\Request;

Route::get('/logs', function (Request $request) {
    $params = $request->validate([
        'search' => 'nullable|string|max:255',
        'per_page' => 'nullable|integer|min:1',
        'page' => 'nullable|integer|min:1',
    ]);

    $params['per_page'] = $params['per_page'] ?? 10;
    $params['page'] = $params['page'] ?? 1; 
    
    $logs = ProductLog::query()
        ->search($params)
        ->with('product', 'user')
        ->latest()
        ->paginate($params['per_page'], ['*'], 'page', $params['page'])
        ->withQueryString();
    return Inertia::render('Logs/Index', compact('logs'));
})->name('logs.index');