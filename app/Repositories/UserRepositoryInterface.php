<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): ?User;

    public function update(array $data, User $user): User;

    public function delete(User $user): void;

}