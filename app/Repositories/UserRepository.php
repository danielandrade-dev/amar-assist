<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserRepository implements UserRepositoryInterface
{
    public function all(array $params): LengthAwarePaginator
    {
        $params['per_page'] = $params['per_page'] ?? 10;
        $params['page'] = $params['page'] ?? 1;
        return User::query()
        ->search($params)
        ->latest()
        ->paginate($params['per_page'], ['*'], 'page', $params['page'])
        ->withQueryString();
    }

    public function create(array $data): ?User
    {
        return User::create($data);
    }

    public function update(array $data, User $user): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

}