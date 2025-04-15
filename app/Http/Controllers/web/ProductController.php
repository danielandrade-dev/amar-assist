<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->with('activeStorage');
        return inertia('products/index', compact('products'));
    }
    public function create()
    {
        return inertia('products/create');
    }
    public function store(ProductRequest $request)
    {
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso');
    }
    public function edit(Product $product)
    {
        return inertia('products/edit', compact('product'));
    }
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso');
    }
}
