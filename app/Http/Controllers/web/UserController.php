<?php

declare(strict_types=1);
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserSearchRequest;
final class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(UserSearchRequest $request): InertiaResponse
    {
        $users = $this->userService->all($request->validated());
        return Inertia::render('User/Index', compact('users'));
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('User/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = $this->userService->create($data);

        if (!$user instanceof User) {
            throw new \UnexpectedValueException('Failed to create user');
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário criado com sucesso');
    }

    public function edit(User $user): InertiaResponse
    {
        if (!$user instanceof User) {
            throw new ModelNotFoundException('User not found');
        }

        return Inertia::render('User/Edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($request->validated(), $user);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário deletado com sucesso');
    }
}