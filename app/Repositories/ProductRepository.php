<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function create(array $data): ?Product
    {
        return Product::create($data);
    }   

    public function update(array $data, Product $product): Product
    {
        $product->update($data);
        return $product;
    }   

    public function delete(Product $product): void
    {
        $product->delete();
    }   

    public function find(int $id): Product
    {
        return Product::find($id);
    }   
    
    public function findBySlug(string $slug): Product
    {
        return Product::where('slug', $slug)->first();
    }   
    
    
}
