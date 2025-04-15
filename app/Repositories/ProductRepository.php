<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final class ProductRepository implements ProductRepositoryInterface
{
    public function all(array $params): Collection
    {
        return Product::query()
        ->with('activeStorage')
        ->search($params)
        ->paginate($params['per_page'], ['*'], 'page', $params['page']);
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
        return Product::query()
        ->with('activeStorage')
        ->find($id);
    }   
    
    public function findBySlug(string $slug): Product
    {
        return Product::query()
        ->with('activeStorage')
        ->where('slug', $slug)
        ->first();
    }   
    
    
}
