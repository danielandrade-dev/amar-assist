<?php

declare(strict_types=1);
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Http\Requests\ProductSearchRequest;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    public function index(ProductSearchRequest $request): InertiaResponse
    {
        $products = $this->productService->all($request->validated());
        return Inertia::render('Product/Index', compact('products'));
    }
    public function create(): InertiaResponse
    {
        return Inertia::render('Product/Create');
    }
    public function store(ProductRequest $request): RedirectResponse
    {
        $product = $this->productService->create($request->all());
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to create product');
        }
        return redirect()
        ->route('products.index')->with('success', 'Produto criado com sucesso');
    }
    public function edit(Product $product): InertiaResponse
    {
        if (!$product instanceof Product) {
            throw new ModelNotFoundException('Product not found');
        }
        return Inertia::render('Product/Edit', compact('product'));
    }
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->productService->update($request->validated(), $product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to update product');
        }
        return redirect()
            ->route('products.index')
            ->with('success', 'Produto atualizado com sucesso');
    }
    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->delete($product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to delete product');
        }
        return redirect()
            ->route('products.index')
            ->with('success', 'Produto deletado com sucesso');
    }
    
    public function changeStatus(Product $product): RedirectResponse
    {
        $this->productService->changeStatus($product);
        return redirect()->route('products.index')->with('success', 'Status do produto alterado com sucesso');
    }
}
