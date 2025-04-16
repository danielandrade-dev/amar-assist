<?php

declare(strict_types=1);
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserSearchRequest;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     description="Api para gerenciamento de usuários",
     *     summary="Lista todos os usuários",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Nome do usuário",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com")
     *             )
     *         )
     *     )
     * )
     */

     public function index(UserSearchRequest $request): JsonResponse
     {
         $users = $this->userService->all($request->validated());
         return response()->json($users);
     }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     description="Api para gerenciamento de usuários",
     *     summary="Cria um novo usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao criar usuário"
     *     )
     * )
     */
    public function store(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());
        
        if (!$user instanceof User) {
            throw new \UnexpectedValueException('Failed to create user');
        }

        return response()->json('Usuário criado com sucesso');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     description="Api para gerenciamento de usuários",
     *     summary="Mostra um usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário retornado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     )
     * )
     */
    public function edit(User $user): JsonResponse
    {
        if (!$user instanceof User) {
            throw new ModelNotFoundException('User not found');
        }

        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     description="Api para gerenciamento de usuários",
     *     summary="Atualiza um usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso"
     *     )
     * )
     */
    public function update(ProfileUpdateRequest $request, User $user): JsonResponse
    {
        $this->userService->update($request->validated(), $user);

        return response()->json('Usuário atualizado com sucesso');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     description="Api para gerenciamento de usuários",
     *     summary="Deleta um usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário deletado com sucesso"
     *     )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return response()->json('Usuário deletado com sucesso');
    }
}