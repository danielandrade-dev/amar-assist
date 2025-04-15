<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): ?Product;

    public function update(array $data, Product $product): Product;

    public function delete(Product $product): void;

    public function find(int $id): Product;

    public function findBySlug(string $slug): Product;
}
