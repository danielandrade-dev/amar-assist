<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

final class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Login",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="email", type="string", example="teste@teste.com"),
 *             @OA\Property(property="password", type="string", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login realizado com sucesso",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="token", type="string", example="1234567890"),
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Camisa Polo"),
 *                     @OA\Property(property="email", type="string", example="teste@teste.com")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro ao realizar login",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="Ocorreu um erro durante a autenticação.")
 *             )
 *         )
 *     )
 * )
 */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if ( ! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['As credenciais fornecidas estão incorretas.'],
                ]);
            }

            $token = $user->createToken(
                'auth-token',
                ['*'],
                now()->addDays(config('auth.token_expiration', 1)),
            )->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user->only(['id', 'name', 'email']),
            ]);

        } catch (Exception $e) {
            Log::error('Erro durante login:', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Ocorreu um erro durante a autenticação.',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout realizado com sucesso',
            ]);

        } catch (Exception $e) {
            Log::error('Erro durante logout:', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Ocorreu um erro ao realizar o logout.',
            ], 500);
        }
    }
}
