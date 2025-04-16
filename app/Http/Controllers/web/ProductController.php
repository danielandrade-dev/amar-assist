<?php

declare(strict_types=1);
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Http\Requests\ProductSearchRequest;
final class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(ProductSearchRequest $request)
    {
        $products = $this->productService->all($request->validated());
        return inertia('Product/Index', compact('products'));
    }
    public function create()
    {
        return inertia('Product/Create');
    }
    public function store(ProductRequest $request)
    {
        $product = $this->productService->create($request->all());
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to create product');
        }
        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso');
    }
    public function edit(Product $product)
    {
        return inertia('Product/Edit', compact('product'));
    }
    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->update($request->all(), $product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to update product');
        }
        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso');
    }
    public function destroy(Product $product)
    {
        $this->productService->delete($product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to delete product');
        }
        return redirect()->route('products.index')->with('success', 'Produto deletado com sucesso');
    }
    public function show(Product $product)
    {
        $product = $this->productService->find($product->id);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to show product');
        }
        return inertia('products/show', compact('product'));
    }
    public function changeStatus(Product $product)
    {
        $this->productService->changeStatus($product);
        return redirect()->route('products.index')->with('success', 'Status do produto alterado com sucesso');
    }
}
