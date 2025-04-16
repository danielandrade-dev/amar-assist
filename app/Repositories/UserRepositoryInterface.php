<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function all(array $params): LengthAwarePaginator;

    public function create(array $data): ?User;

    public function update(array $data, User $user): bool;

    public function delete(User $user): void;

}