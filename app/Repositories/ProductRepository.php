<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

final class ProductRepository implements ProductRepositoryInterface
{
    public function all(array $params): LengthAwarePaginator
    {   
        $params['per_page'] = $params['per_page'] ?? 10;
        $params['page'] = $params['page'] ?? 1;
        return Product::query()
        ->with('activeStorage')
        ->search($params)
        ->orderBy('created_at', 'desc')
        ->paginate($params['per_page'], ['*'], 'page', $params['page']);
    }

    public function create(array $data): ?Product
    {
        return Product::create($data);
    }   

    public function update(array $data, Product $product): bool
    {
        return $product->update($data);
    }   

    public function delete(Product $product): void
    {
        $product->delete();
    }   

    public function changeStatus(Product $product): void
    {
        $product->update(['status' => !$product->status]);
    }
    
    
}
