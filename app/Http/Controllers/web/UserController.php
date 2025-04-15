<?php

declare(strict_types=1);
namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;


final class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(): Response
    {
        $users = $this->userService->all();

        return Inertia::render('users/index', compact('users'));
    }

    public function create(): Response
    {
        return Inertia::render('users/create');
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

        return redirect()->route('users.show', $user->id);
    }

    public function show(int $id): Response
    {
        $user = $this->userService->find($id);

        if (!$user instanceof User) {
            throw new ModelNotFoundException('User not found');
        }

        return Inertia::render('users/show', compact('user'));
    }

    public function edit(int $id): Response
    {
        $user = $this->userService->find($id);

        return Inertia::render('users/edit', compact('user'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'password' => 'sometimes|confirmed'
        ]);

        $user = $this->userService->update($data, $id);

        if ($user <= 0) {
            throw new \UnexpectedValueException('Failed to update user');
        }

        return redirect()->route('users.show', $id);
    }

    public function destroy(int $id): RedirectResponse
    {
        $result = $this->userService->delete($id);

        if (!is_bool($result)) {
            throw new \UnexpectedValueException('Failed deleting user');
        }

        return redirect()->route('users.index');
    }
}