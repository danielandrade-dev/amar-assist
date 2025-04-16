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
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

final class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(): Response
    {
        return Inertia::render('User/Index', [
            'users' => User::query()
                ->when(request('search'), function($query, $search) {
                    $query->where(function($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->paginate(10)
                ->withQueryString()
        ]);
    }

    public function create(): Response
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

    public function edit(User $user): Response
    {
        if (!$user instanceof User) {
            throw new ModelNotFoundException('User not found');
        }

        return Inertia::render('User/Edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request, User $user): RedirectResponse
    {
        try {
            $validatedData = $request->validated();
            
            $user->fill($validatedData);
            
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            
            if (isset($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            
            $user->save();
            
            return redirect()
                ->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Erro ao atualizar usuário']);
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $result = $this->userService->delete($user->id);

        if (!is_bool($result)) {
            throw new \UnexpectedValueException('Failed deleting user');
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário deletado com sucesso');
    }
}