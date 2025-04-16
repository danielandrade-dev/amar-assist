<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function all(array $params): LengthAwarePaginator;

    public function create(array $data): ?Product;

    public function update(array $data, Product $product): Product;

    public function delete(Product $product): void;

    public function changeStatus(Product $product): void;
}
