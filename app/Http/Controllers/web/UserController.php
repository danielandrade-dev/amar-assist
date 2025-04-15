<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Inertia\Inertia;
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return inertia::render('users/index', compact('users'));
    }
    public function create()
    {
        return inertia::render('users/create');
    }
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso');
    }
    public function edit(User $user)
    {
        return inertia::render('users/edit', compact('user'));
    }
    public function update(ProfileUpdateRequest $request, User $user)
    {
        $user->update($request->all());
        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário deletado com sucesso');
    }
}

