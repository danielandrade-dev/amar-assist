<?php

declare(strict_types=1);
namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function all(array $params): LengthAwarePaginator
    {
        return $this->productRepository->all($params);
    }

    public function create(array $data): ?Product
    {
        return $this->productRepository->create($data);
    }
    
    public function update(array $data, Product $product): bool
    {
        return $this->productRepository->update($data, $product);
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }
    

    public function changeStatus(Product $product): void
    {
        $this->productRepository->changeStatus($product);
    }
}
