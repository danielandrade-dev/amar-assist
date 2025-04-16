<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            throw new \InvalidArgumentException('A senha é obrigatória para criar um usuário.');
        }
        
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