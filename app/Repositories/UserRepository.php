<?php

declare(strict_types=1);
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function create(array $data): ?User
    {
        return User::create($data);
    }

    public function update(array $data, User $user): User
    {
        return $user->update($data);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

}