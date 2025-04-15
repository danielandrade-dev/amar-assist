<?php

declare(strict_types=1);
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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

    public function update(array $data, int $id): int
    {
        return $this->userRepository->update($data, $id);
    }

    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }

    public function all(): Collection
    {
        return $this->userRepository->all()->paginate(10);
    }

    public function find(int $id): ?User
    {
        return $this->userRepository->find($id);
    }
}