<?php

declare(strict_types=1);
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */
final class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Lista todos os produtos",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Nome do produto",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Data de início",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="Data de término",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Quantidade de produtos por página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Camisa Polo"),
     *                 @OA\Property(property="description", type="string", example="Camisa polo branca com estampa azul"),
     *                 @OA\Property(property="price", type="number", format="float", example=79.90),
     *                 @OA\Property(property="cost", type="number", format="float", example=50.00),
     *                 @OA\Property(property="status", type="boolean", example=true),
     *                 @OA\Property(property="slug", type="string", example="camisa-polo-branca-azul")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - token ausente ou inválido",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="Unauthorized")
     *             )
     *         )
     *     )
     * )
     */

    public function index(ProductSearchRequest $request): JsonResponse
    {
        $products = $this->productService->all($request->validated());
        return response()->json(ProductResource::collection($products));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Mostra um produto", 
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto retornado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Camisa Polo"),
     *             @OA\Property(property="description", type="string", example="Camisa polo branca com estampa azul"),
     *             @OA\Property(property="price", type="number", format="float", example=79.90),
     *             @OA\Property(property="cost", type="number", format="float", example=50.00),
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="slug", type="string", example="camisa-polo-branca-azul")
     *         )
     *     )
     * )
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json(new ProductResource($product));
    }
    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Cria um novo produto",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Camisa Polo"),
     *             @OA\Property(property="description", type="string", example="Camisa polo branca com estampa azul"),
     *             @OA\Property(property="price", type="number", format="float", example=79.90),
     *             @OA\Property(property="cost", type="number", format="float", example=50.00),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto criado com sucesso")
     *         )
     *     )
     * )
     */

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->all());
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to create product');
        }
        return response()->json('Produto criado com sucesso');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Atualiza um produto",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Camisa Polo"),
     *             @OA\Property(property="description", type="string", example="Camisa polo branca com estampa azul"),
     *             @OA\Property(property="price", type="number", format="float", example=79.90),
     *             @OA\Property(property="cost", type="number", format="float", example=50.00),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto atualizado com sucesso")
     *         )
     *     )
     * )
     */

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $this->productService->update($request->validated(), $product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to update product');
        }
        return response()->json('Produto atualizado com sucesso');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Deleta um produto",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Produto deletado com sucesso")
     *         )
     *     )
     * )
     */

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);
        if (!$product instanceof Product) {
            throw new \UnexpectedValueException('Failed to delete product');
        }
        return response()->json('Produto deletado com sucesso');
    }
    
    /**
     * @OA\Post(
     *     path="/api/v1/products/{id}/change-status",
     *     tags={"Products"},
     *     description="Api para gerenciamento de produtos",
     *     summary="Altera o status de um produto para ativo ou inativo",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status do produto alterado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Status do produto alterado com sucesso")
     *         )
     *     )
     * )
     */

    public function changeStatus(Product $product): JsonResponse
    {
        $this->productService->changeStatus($product);
        return response()->json('Status do produto alterado com sucesso');
    }
}
