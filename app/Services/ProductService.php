<?php

declare(strict_types=1);
namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function all(): Collection
    {
        return $this->productRepository->all();
    }

    public function create(array $data): ?Product
    {
        return $this->productRepository->create($data);
    }
    
    public function update(array $data, Product $product): Product
    {
        return $this->productRepository->update($data, $product);
    }

    public function delete(Product $product): void
    {
        $this->productRepository->delete($product);
    }

    public function find(int $id): Product
    {
        return $this->productRepository->find($id);
    }
    
    public function findBySlug(string $slug): Product
    {
        return $this->productRepository->findBySlug($slug);
    }

}
