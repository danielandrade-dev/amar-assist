<?php

declare(strict_types=1);
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function update(array $data, User $user): bool
    {
        return $this->userRepository->update($data, $user);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

    public function all(array $params): LengthAwarePaginator
    {
        return $this->userRepository->all($params);
    }
}